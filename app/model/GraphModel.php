<?php
namespace App\Model;
use App\Model\DataMapper;
// require_once dirname(__DIR__, 2) . "/vendor/autoload.php";
class Graph {
    private string $idDevice;
    private int $year;
    private int $selectedTime;
    private string $range = "";
    private int $width;
    private int $height;

    public function __construct(string $idDevice, int $year, int $selectedTime, string $range = ""){
        $this->idDevice = $idDevice;
        $this->year = $year;
        $this->selectedTime = $selectedTime;
        $this->range = $range;
        $this->width = 800;
        $this->height = 800;
    }

    public function draw(){
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
class GraphModel extends DataMapper {

    protected static function gatherData($idDevice, $year, $desiredMonth, $range = "") {
        if($range == "semester"){
            return parent::select(["'' AS date", 'IF(MONTH(dateTime) < 7, 1,2) as semester', 'MONTHNAME(dateTime) as month', 'device.nameDevice', 'TRIM(download)+0 AS download', 'TRIM(upload)+0 AS upload'], 'device RIGHT JOIN transaction ON device.idDevice = transaction.idDevice', ['device.idDevice' => $idDevice, 'YEAR(dateTime)' => $year, 'IF(MONTH(dateTime) < 7, 1,2)' => $desiredMonth], '', "GROUP BY month ORDER BY dateTime ASC")->fetch_all(MYSQLI_ASSOC);
        }
        return parent::select(['DAYOFMONTH(dateTime) AS date', 'MONTHNAME(dateTime) AS month', 'device.nameDevice', 'TRIM(DOWNLOAD)+0 AS download', 'TRIM(upload)+0 AS upload'], 'device RIGHT JOIN transaction ON device.idDevice = transaction.idDevice', ['device.idDevice' => $idDevice, 'MONTH(dateTime)' => $desiredMonth, 'YEAR(dateTime)' => $year], '', 'ORDER BY dateTime ASC')->fetch_all(MYSQLI_ASSOC);
    }

    public static function prepareData($idDevice, $year, $desiredMonth, $range = "") {
        $datas = self::gatherData($idDevice, $year, $desiredMonth, $range);
        if(!$datas){
            return[[0], 'NO DATA', [0], [0]];
        }
        foreach ($datas as $data){
            $date[] = $data['month'] . " " . $data['date'];
            $download[] = $data['download'];
            $upload[] = $data['upload'];
        }
        $nameDevice = $data['nameDevice'];

        return [$date, $nameDevice, $download, $upload];
    }
}