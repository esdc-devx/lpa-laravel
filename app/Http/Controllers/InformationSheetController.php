<?php

namespace App\Http\Controllers;

use App\Models\InformationSheet\InformationSheet;
use Illuminate\Http\Request;

class InformationSheetController extends APIController
{
    /**
     * Show all available information sheets for a specific entity.
     *
     * @param  App\Models\BaseModel $entityType
     * @param  int $entityId
     * @return \Illuminate\Http\Response
     */
    public function show($entityType, $entityId)
    {
        $informationSheets = $entityType->findOrFail($entityId)->informationSheets;

        return $this->respond([
            'information_sheets' => $informationSheets,
        ]);
    }

    /**
     * Render an information sheet for display and/or print.
     *
     * @param  InformationSheet $informationSheet
     * @return view
     */
    public function render(InformationSheet $informationSheet)
    {
        return view("information_sheets.sheets.{$informationSheet->viewFilePath}", [
            'definition' => $informationSheet->definition->toArray(),
            'data'       => $informationSheet->getData(),
        ]);
    }

    /**
     * Return raw data provided for a specific information sheet.
     * Useful when debugging or creating new information sheets.
     *
     * @param  InformationSheet $informationSheet
     * @return \Illuminate\Http\Response
     */
    public function data(InformationSheet $informationSheet)
    {
        return [
            'definition' => $informationSheet->definition->toArray(),
            'data'       => $informationSheet->getData(),
        ];
    }
}
