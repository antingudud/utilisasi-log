<?php
namespace App\View;
use App\Model\GraphModel;

class Chart extends GraphModel{
    public string $idDevice;
    public int $year;
    public int $selectedTime;
    public string $range = "";
    public int $width;
    public int $height;
    
    public function __construct(string $idDevice, int $year, int $selectedTime, string $range = ""){
        $this->idDevice = $idDevice;
        $this->year = $year;
        $this->selectedTime = $selectedTime;
        $this->range = $range;
        $this->width = 800;
        $this->height = 800;
    }
    public function drawChart(){
        list($date, $nameDevice, $download, $upload) = (new GraphModel)->prepareData($this->idDevice, $this->year, $this->selectedTime, $this->range);
        $graph = new \Graph($this->width,$this->height);
        $graph->SetScale('textlin');
        $graph->title->Set($nameDevice);
        $graph->xaxis->title->Set('Tanggal');
        
        $graph->xaxis->SetTickLabels($date);
        $graph->xaxis->SetLabelAngle(45);
        $graph->yaxis->title->Set('Penggunaan (MB)');

        $downloadBarPlot = new \BarPlot($download);
        $downloadBarPlot->SetLegend('Download');

        $uploadBarPlot = new \BarPlot($upload);
        $uploadBarPlot->SetLegend('Upload');

        $groupedBarPlot = new \GroupBarPlot(Array( $downloadBarPlot, $uploadBarPlot));
        $graph->legend->SetFrameWeight(1);
        $graph->legend->SetColumns(2);
        $graph->legend->SetPos(0.5,0.05,'center', 'top');
        $graph->Add($groupedBarPlot);
        $downloadBarPlot->value->Show();
        $uploadBarPlot->value->Show();
        $downloadBarPlot->value->SetAngle(90);
        $uploadBarPlot->value->SetAngle(90);
        $downloadBarPlot->SetValuePos("center");
        $uploadBarPlot->SetValuePos("center");
        $graph->Stroke();
    }
    public function __destruct()
    {
        unset($this->idDevice, $this->year, $this->selectedTime, $this->range, $this->width, $this->height);
    }
}