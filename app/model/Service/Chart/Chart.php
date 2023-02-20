<?php
namespace App\Model\Service\Chart;

use App\Core\Database\AdapterInterface;
use App\Model\Repository\Transaction\Repo;
use App\Model\Service\Chart\DrawChartInterface;

class DrawChart implements DrawChartInterface
{
    private $repo;
    private $idDevice;
    private $year;
    private $selectedTime;
    private $range;
    private $width;
    private $height;
    private $date;
    private $nameDevice;
    private $download;
    private $upload;
    
    public function __construct(string $idDevice, int $year, int $selectedTime, string $range = "", AdapterInterface $adapter)
    {
        $this->idDevice = $idDevice;
        $this->year = $year;
        $this->selectedTime = $selectedTime;
        $this->range = $range;
        $this->width = 800;
        $this->height = 800;
        $this->repo = new Repo($adapter);
    }

    public function draw()
    {
        $this->getData();
        $graph = new \Graph($this->width,$this->height);
        $graph->SetScale('textlin');
        $graph->title->Set($this->nameDevice);
        $graph->xaxis->title->Set('Tanggal');
        
        $graph->xaxis->SetTickLabels($this->date);
        $graph->xaxis->SetLabelAngle(45);
        $graph->yaxis->title->Set('Penggunaan (MB)');

        $downloadBarPlot = new \BarPlot($this->download);
        $downloadBarPlot->SetLegend('Download');

        $uploadBarPlot = new \BarPlot($this->upload);
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

    public function getData()
    {
        if($this->range == "semester")
        {
            return list($this->date, $this->nameDevice, $this->download, $this->upload) = $this->repo->fetchSemesterChart($this->idDevice, $this->year, $this->selectedTime);
        } else
        {
            return list($this->date, $this->nameDevice, $this->download, $this->upload) = $this->repo->fetchMonthChart($this->idDevice, $this->year, $this->selectedTime);
        }
    }
}