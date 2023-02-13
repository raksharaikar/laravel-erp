<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Bom;
use App\Models\Part;
use App\Imports\BOMImport;
use Maatwebsite\Excel\Facades\Excel;

class BomController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function importForm()
    {
        return view('boms.import');
    }

    public function index()
    {

        $boms = Part::where(function ($query) {
            $query->whereNotNull('semi_part_bom_version')
                ->orWhere('parentID', 0);
        })->get();
        return view('boms.index', compact('boms'));

    }



    public function destroy($bom)
    {
        return $this->deleteBOMItem($bom);
    }


    public function deleteBOMItem($bom)
    {
        Part::where('id', $bom)->delete();
        return redirect()->back()->with('success', 'BOM item deleted successfully!');
    }


    public function show($bom)
    {

        return view('boms.show', compact('id'));
    }


    public function getChildParts($part, $semiPartVesion)
    {
        $boms = Part::where('partID', $part->partID)
            ->where('bom_version', $semiPartVesion)
            ->with([
                'childparts' => function ($query) use ($semiPartVesion) {
                    $query->where('bom_version', $semiPartVesion);
                }
            ])
            ->get();

        foreach ($boms as $bom) {
            if (count($bom->childparts) > 0) {
                $this->getChildParts($bom, $semiPartVesion);
            }
        }
    }


    public function showCompareForm()
    {
        return view('boms.compare');
    }


    public function recursiveCheck($subChildpart, &$boms1Data)
    {
        if (count($subChildpart->childparts) > 0) {
            foreach ($subChildpart->childparts as $subsubChildpart) {
                if (array_key_exists($subsubChildpart->partID, $boms1Data)) {
                    $boms1Data[$subsubChildpart->partID]['quantity'] += $subsubChildpart->quantity;
                } else {
                    $boms1Data[$subsubChildpart->partID] = [
                        'partID' => $subsubChildpart->partID,
                        'part_name' => $subsubChildpart->part_name,
                        'quantity' => $subsubChildpart->quantity
                    ];
                }
                $this->recursiveCheck($subsubChildpart, $boms1Data);
            }
        }
    }

    public function compareBoms(Request $request)
    {
        $parentID = $request->input('parentID');
        $bom_version_1 = $request->input('bom_version_1');
        $bom_version_2 = $request->input('bom_version_2');



        function getChildParts2($query, $bom_version)
        {
            $query->where('bom_version', $bom_version)
                ->with([
                    'childparts' => function ($query) use ($bom_version) {
                        getChildParts2($query, $bom_version);
                    }
                ]);
        }

        $boms1 = Part::where('partID', $parentID)
            ->where('bom_version', $bom_version_1)
            ->with([
                'childparts' => function ($query) use ($bom_version_1) {
                    getChildParts2($query, $bom_version_1);
                }
            ])
            ->get();


        function getChildParts1($query, $bom_version_2)
        {
            $query->where('bom_version', $bom_version_2)
                ->with([
                    'childparts' => function ($query) use ($bom_version_2) {
                        getChildParts1($query, $bom_version_2);
                    }
                ]);
        }

        $boms2 = Part::where('partID', $parentID)
            ->where('bom_version', $bom_version_2)
            ->with([
                'childparts' => function ($query) use ($bom_version_2) {
                    getChildParts1($query, $bom_version_2);
                }
            ])
            ->get();



        $boms1Data = [];
        $boms2Data = [];

        foreach ($boms1 as $bom1) {
            $boms1Data[$bom1->partID] = [
                'partID' => $bom1->partID,
                'part_name' => $bom1->part_name,
                'quantity' => $bom1->quantity
            ];
            if (count($bom1->childparts) > 0) {
                foreach ($bom1->childparts as $childpart) {
                    if (array_key_exists($childpart->partID, $boms1Data)) {
                        $boms1Data[$childpart->partID]['quantity'] += $childpart->quantity;
                    } else {
                        $boms1Data[$childpart->partID] = [
                            'partID' => $childpart->partID,
                            'part_name' => $childpart->part_name,
                            'quantity' => $childpart->quantity
                        ];
                    }
                    if (count($childpart->childparts) > 0) {
                        foreach ($childpart->childparts as $subChildpart) {
                            if (array_key_exists($subChildpart->partID, $boms1Data)) {
                                $boms1Data[$subChildpart->partID]['quantity'] += $subChildpart->quantity;
                            } else {
                                $boms1Data[$subChildpart->partID] = [
                                    'partID' => $subChildpart->partID,
                                    'part_name' => $subChildpart->part_name,
                                    'quantity' => $subChildpart->quantity
                                ];
                            }
                            if (count($subChildpart->childparts) > 0) {
                                $this->recursiveCheck($subChildpart, $boms1Data);
                            }
                        }
                    }
                }
            }
        }






        foreach ($boms2 as $bom1) {
            $boms2Data[$bom1->partID] = [
                'partID' => $bom1->partID,
                'part_name' => $bom1->part_name,
                'quantity' => $bom1->quantity
            ];
            if (count($bom1->childparts) > 0) {
                foreach ($bom1->childparts as $childpart) {
                    if (array_key_exists($childpart->partID, $boms2Data)) {
                        $boms2Data[$childpart->partID]['quantity'] += $childpart->quantity;
                    } else {
                        $boms2Data[$childpart->partID] = [
                            'partID' => $childpart->partID,
                            'part_name' => $childpart->part_name,
                            'quantity' => $childpart->quantity
                        ];
                    }
                    if (count($childpart->childparts) > 0) {
                        foreach ($childpart->childparts as $subChildpart) {
                            if (array_key_exists($subChildpart->partID, $boms2Data)) {
                                $boms2Data[$subChildpart->partID]['quantity'] += $subChildpart->quantity;
                            } else {
                                $boms2Data[$subChildpart->partID] = [
                                    'partID' => $subChildpart->partID,
                                    'part_name' => $subChildpart->part_name,
                                    'quantity' => $subChildpart->quantity
                                ];
                            }

                            if (count($subChildpart->childparts) > 0) {
                                $this->recursiveCheck($subChildpart, $boms1Data);
                            }
                        }
                    }
                }
            }
        }

 
    return view('boms.compare', compact('boms1', 'boms2', 'boms1Data', 'boms2Data'));

    }


    public function showParts($partID, $bomVersion)
    {





        function getChildParts($query, $bom_version)
        {
            $query->where('bom_version', $bom_version)
                ->with([
                    'childparts' => function ($query) use ($bom_version) {
                        getChildParts($query, $bom_version);
                    }
                ]);
        }

        $boms = Part::where('partID', $partID)
            ->where('bom_version', $bomVersion)
            ->with([
                'childparts' => function ($query) use ($bomVersion) {
                    getChildParts($query, $bomVersion);
                }
            ])
            ->get();

        return view('boms.showParts', compact('boms', 'partID'));




    }


    public function import(Request $request)
    {




        $file = $request->file('file');
        $fileName = time() . '-' . $file->getClientOriginalName();
        Storage::disk('local')->putFileAs('public/files', $file, $fileName);

        $path = storage_path('app\public\files\\' . $fileName);

        //  $file = $request->file('file');


        // Read the data from the Excel sheet and store it in an array
        // $filePath = Storage::disk('public')->path('bom.xlsx');
        $spreadsheet = IOFactory::load($path);

        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();

        unset($rows[0]);


        $bomData = [];


        foreach ($rows as $row) {




            $bomData[] = [
                'bom_id' => $row[0],
                'part_id' => $row[1],
                'semi_part_bom_version' => ($row[2] === 'null' || is_null($row[2])) ? null : $row[2],
                'quantity' => $row[3],
                'loss_rate' => $row[4],
                'parent_id' => (($row[5] === "null") ? "null" : ($row[5] === 0) ? 0 : $row[5]),
                'bom_version' => $row[6],

            ];
        }

        //Insert the data into the BOM table and the BOM parts table
        DB::transaction(function () use ($bomData) {
            foreach ($bomData as $data) {



                $bom = new Part;
                $bom = Part::firstOrCreate(
                    ['bomID' => $data['bom_id']],
                    [
                        'partID' => $data['part_id'],
                        'semi_part_bom_version' => $data['semi_part_bom_version'],
                        'quantity' => $data['quantity'],
                        'loss_rate' => $data['loss_rate'],
                        'parentID' => $data['parent_id'],
                        'bom_version' => $data['bom_version']
                    ]
                );
                $bom->save();

            }
        });

        return redirect()->route('boms.index')->with('success', 'BOM imported successfully!');
    }
}