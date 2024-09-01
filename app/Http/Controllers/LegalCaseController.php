<?php

namespace App\Http\Controllers;

use Auth;
use App\Http\Traits\ImageTrait;
use App\Models\CasePlaintiff;
use App\Models\CaseDefendant;
use App\Models\LegalCase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class LegalCaseController extends Controller
{
    use ImageTrait;

    public function index()
    {
        $legalCases = LegalCase::with(['plaintiff','defendant'])->orderByDesc('trial_date')->get();
        return view("backEnd.legalCase.index", ['legalCases' => $legalCases]);
    }

    public function create()
    {
        return view("backEnd.legalCase.create");
    }

    public function store(Request $request) 
    {
        DB::beginTransaction();
        try {
            
            $validatedData = $request->validate([
                'case_number' => 'required',
                'clarification' => 'required|string',
                'trial_date' => 'required|date',
                'mediator' => 'required|string',
                'notes' => 'required|string',
                'description' => 'nullable|string',
                'file_sk' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
                'file_suit' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
                'file_proof' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            ]);
    
            $data = [];
    
            foreach (['file_sk', 'file_suit', 'file_proof'] as $file) {
                if ($request->hasFile($file)) {
                    $data[$file] = $this->storeImage('legalCase', $request->file($file));
                }
            }
    
            $legalCase = LegalCase::create(array_merge($validatedData, [
                'file_sk' => $data['file_sk'] ?? null,
                'file_suit' => $data['file_suit'] ?? null,
                'file_proof' => $data['file_proof'] ?? null,
                'created_by' => Auth::id(),
            ]));

            if($legalCase->id){
                $this->savePlaintiff($request->all(), $legalCase);
                $this->saveDefendant($request->all(), $legalCase);
                DB::commit();
                Log::channel('log-transaction')->info('Pencatatan Kasus Created', ['User' =>  Auth::user()->name]);
                return redirect()->route('legal_case.index')->with('success', 'Data berhasil Ditambahkan');
            }

        } catch (\Exception $e) {
            DB::rollback();
            Log::channel('log-transaction')->error($e->getMessage(), ['User' =>  Auth::user()->name]);
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(LegalCase $legalCase)
    {
        if (!$legalCase) {
            return response()->json([
                'message' => 'Data Pencatatan Kasus tidak ada.',
                'status' => false,
            ], JsonResponse::HTTP_NOT_FOUND);
        }

        $legalCase->trial_date = Carbon::parse($legalCase->trial_date)->translatedFormat('l, d F Y');
        return response()->json([
            'message' => 'Data Pencatatan Berhasil.',
            'status' => true,
            'data' => $legalCase,
            'file_sk' => $legalCase->file_sk ? asset('storage/legalCase/'.$legalCase->file_sk) : "#",
            'file_suit' => $legalCase->file_suit ? asset('storage/legalCase/'.$legalCase->file_suit) : "#",
            'file_proof' => $legalCase->file_proof ?  asset('storage/legalCase/'.$legalCase->file_proof) : "#",
            'penggugat' => $legalCase->getPlaintiffNamesHtml(),
            'tergugat' => $legalCase->getDefendantNamesHtml(),
        ], JsonResponse::HTTP_OK);
    }

    public function edit(LegalCase $legalCase)
    {
        return view('backEnd.legalCase.edit', ['legalCase' => $legalCase]);
    }

    public function update(Request $request, LegalCase $legalCase) 
    {
        
        DB::beginTransaction();
        try {
            $validatedData = $request->validate([
                'case_number' => 'required',
                'clarification' => 'required|string',
                'trial_date' => 'required|date',
                'mediator' => 'required|string',
                'notes' => 'required|string',
                'description' => 'nullable|string',
                'file_sk' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
                'file_suit' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
                'file_proof' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:2048',
            ]);

            $data = [];
            foreach (['file_sk', 'file_suit', 'file_proof'] as $file) {
                if ($request->hasFile($file)) {
                    $data[$file] = $this->storeImage('legalCase', $request->file($file));
                }
            }

            foreach (['file_sk', 'file_suit', 'file_proof'] as $file) {
                if ($request->hasFile($file) && !empty($legalCase->$file) && Storage::disk('public')->exists("legalCase/{$legalCase->$file}")) {
                    Storage::disk('public')->delete("legalCase/{$legalCase->$file}");
                }
            }

            $legalCase->update(array_merge($validatedData, [
                'file_sk' => $data['file_sk'] ?? $legalCase->file_sk,
                'file_suit' => $data['file_suit'] ?? $legalCase->file_suit,
                'file_proof' => $data['file_proof'] ?? $legalCase->file_proof,
                'updated_by' => Auth::id(),
            ]));

            if($legalCase->id){
                $legalCase->plaintiff()->delete();
                $legalCase->defendant()->delete();
                $this->savePlaintiff($request->all(), $legalCase);
                $this->saveDefendant($request->all(), $legalCase);
                DB::commit();
                Log::channel('log-transaction')->info('Pencatatan Kasus Updated', ['User' =>  Auth::user()->name]);
                return redirect()->route('legal_case.index')->with('success', 'Data berhasil DiUpdate');
            }

        } catch (\Exception $e) {
            DB::rollback();
            Log::channel('log-transaction')->error($e->getMessage(), ['User' =>  Auth::user()->name]);
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(LegalCase $legalCase)
    {
        DB::beginTransaction();

        try {
            if (!$legalCase) {
                return response()->json([
                    'message' => 'Data Pencatatan Kasus tidak ada.',
                    'status' => false,
                ], JsonResponse::HTTP_NOT_FOUND);
            }

            $files = ['file_sk', 'file_suit', 'file_proof'];

            foreach ($files as $file) {
                if (!empty($legalCase->$file) && Storage::disk('public')->exists("legalCase/{$legalCase->$file}")) {
                    Storage::disk('public')->delete("legalCase/{$legalCase->$file}");
                }
            }

            $legalCase->plaintiff()->delete();
            $legalCase->defendant()->delete();

            $legalCase->update([
                'deleted_by' => Auth::id(),
                'deleted_at' => now()
            ]);

            DB::commit();

            Log::channel('log-transaction')->info('Pencatatan Kasus Delete Success!', ['User' => Auth::user()->name]);

            return response()->json([
                'message' => 'Data Berhasil Di Hapus.',
                'status' => true,
            ], JsonResponse::HTTP_OK);

        } catch (\Exception $e) {
            DB::rollback();

            Log::channel('log-transaction')->error($e->getMessage(), ['User' => Auth::user()->name]);

            return response()->json([
                'message' => 'Data Gagal Di Hapus.',
                'status' => false,
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function savePlaintiff($data, $legalCase)
    {
        $dataPlaintiff = [];
        if(!empty($data['name_penggugat'])){
            foreach($data['name_penggugat'] as $key => $name_penggugat)
            {
                $dataPlaintiff[] = new CasePlaintiff([
                    'name' => $name_penggugat,
                    'place_of_birth' => $data['place_of_birth_penggugat'][$key],
                    'date_of_birth' => $data['date_of_birth_penggugat'][$key],
                ]);
            }
        }

        return $legalCase->plaintiff()->saveMany($dataPlaintiff);

    }
    private function saveDefendant($data, $legalCase)
    {
        $dataDefendant = [];
        if(!empty($data['name_tergugat'])){
            foreach($data['name_tergugat'] as $key => $name_tergugat)
            {
                $dataDefendant[] = new CaseDefendant([
                    'name' => $name_tergugat,
                    'place_of_birth' => $data['place_of_birth_tergugat'][$key],
                    'date_of_birth' => $data['date_of_birth_tergugat'][$key],
                ]);
            }
        }

        return $legalCase->defendant()->saveMany($dataDefendant);
    }
}
