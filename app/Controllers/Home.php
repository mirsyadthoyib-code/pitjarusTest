<?php

namespace App\Controllers;

use App\Models\ChartPerRegionModel;
use App\Models\TableBrandRegionModel;

class Home extends BaseController
{
    protected $chartModel;
    protected $tableModel;

    protected $area_name = [
        'Bali', 'DKI Jakarta', 'Jawa Barat', 'Jawa Tengah', 'Kalimantan'
    ];

    protected $form = [
        'area_name' => ['Bali', 'DKI Jakarta', 'Jawa Barat', 'Jawa Tengah', 'Kalimantan'],
        'date_from' => '2021-01-01',
        'date_to' => '2021-01-05'
    ];

    function __construct()
    {
        $this->chartModel = new ChartPerRegionModel();
        $this->tableModel = new TableBrandRegionModel();
    }

    public function index()
    {
        if ($this->request->is('post')) {
            // The form is not submitted, so pass this method.
            $this->form = $this->getForm();
        }

        $data_chart = $this->getChartData();
        $data_table = $this->getTableData();

        foreach ($data_chart as $row) {
            $area_name[] =  $row['area_name'];
            $nilai[] = $row['nilai'];
        };

        foreach ($data_table as $row) {
            $tableData[$row['brand_name']][$row['area_name']] = $row['nilai'];
        }

        $data = [
            'area_name_dropdown' => $this->area_name,
            'form' => $this->form,
            'area_name' => $area_name,
            'nilai' => $nilai,
            'table' => $tableData
        ];

        return view('dashboard.html', $data);
    }

    public function getForm()
    {
        helper('form');
        $form = $this->request->getPost(['area_name', 'date_from', 'date_to']);
        return $form;
    }

    public function getChartData()
    {
        $data = $this->chartModel->getChartPerRegion($this->form['area_name'], $this->form['date_from'], $this->form['date_from']);
        return $data;
    }

    public function getTableData()
    {
        $data = $this->tableModel->getTableBrandRegion($this->form['area_name'], $this->form['date_from'], $this->form['date_from']);
        return $data;
    }
}
