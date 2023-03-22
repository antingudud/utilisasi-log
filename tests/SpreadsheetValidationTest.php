<?php
declare(strict_types=1);

use App\Controller\SpreadsheetController;
use PHPUnit\Framework\TestCase;

class Mock extends SpreadsheetController
{
    public function valisus($data)
    {
        return $this->validate($data);
    }
}

class SpreadsheetValidationTest extends TestCase
{
    public $mock;
    public $mockData = [
        "month" => 3,
        "year" => 2022,
        "devices" => [
            "43ert2sf",
            "564edws",
            "4s234uy"
        ]
    ];

    public function testValidInputWillPassTests()
    {
        $true = (new Mock())->valisus($this->mockData);
        $this->assertTrue($true);
        $this->assertIsBool($true);
        $this->assertIsNotArray($true);
    }

    public function testDataWithMoreThan3SubArraysWillNotPass()
    {
        $data = $this->mockData;
        $data["anotherone"] = "Wow";
        $errorTest = (new Mock())->valisus($data);
        $this->assertIsNotBool($errorTest);
        $this->assertIsArray($errorTest);
        $this->assertArrayHasKey('status', $errorTest);
        $this->assertArrayHasKey('message', $errorTest);
        $this->assertEquals('Invalid request, please check your input again.', $errorTest["message"], "Error message: " . $errorTest["message"]);
        $this->assertEquals('exception', $errorTest["status"]);
    }

    public function testDataWithLessThan3SubArraysWillNotPass()
    {
        $data = $this->mockData;
        unset($data["month"]);
        $errorTest = (new Mock())->valisus($data);
        $this->assertIsNotBool($errorTest);
        $this->assertIsArray($errorTest);
        $this->assertArrayHasKey('status', $errorTest);
        $this->assertArrayHasKey('message', $errorTest);
        $this->assertEquals('Invalid request, please check your input again.', $errorTest["message"], "Error message: " . $errorTest["message"]);
        $this->assertEquals('exception', $errorTest["status"]);
    }

    public function testDataWith3SubArraysButInvalidKeyWillNotPass()
    {
        $data = $this->mockData;
        unset($data["month"]);
        $data["awllga"] = "aweea";
        $errorTest = (new Mock())->valisus($data);
        $this->assertIsNotBool($errorTest);
        $this->assertIsArray($errorTest);
        $this->assertArrayHasKey('status', $errorTest);
        $this->assertArrayHasKey('message', $errorTest);
        $this->assertEquals('Incomplete request, please check your input again.', $errorTest["message"], "Error message: " . $errorTest["message"]);
        $this->assertEquals('exception', $errorTest["status"]);
    }

    public function testNumericStringMonthWillStillReturnTrue()
    {
        $data = $this->mockData;
        $data["month"] = "3";
        $true = (new Mock())->valisus($data);
        $this->assertTrue($true);
        $this->assertIsBool($true);
        $this->assertIsNotArray($true);
    }

    public function testNumericStringMonthWithWordsWillNotPass()
    {
        $data = $this->mockData;
        $data["month"] = "3a";
        $errorTest = (new Mock())->valisus($data);
        $this->assertIsNotBool($errorTest);
        $this->assertIsArray($errorTest);
        $this->assertArrayHasKey('status', $errorTest);
        $this->assertArrayHasKey('message', $errorTest);
        $this->assertEquals($errorTest["status"], "exception");
    }

    public function testNumericStringMonthWithSpecialCharactersWillNotPass()
    {
        $data = $this->mockData;
        $data["month"] = "3@";
        $errorTest = (new Mock())->valisus($data);
        $this->assertIsNotBool($errorTest);
        $this->assertIsArray($errorTest);
        $this->assertArrayHasKey('status', $errorTest);
        $this->assertArrayHasKey('message', $errorTest);
        $this->assertEquals($errorTest["status"], "exception");
    }

    public function testWhenMonthValueIsSpecialCharacterWillNotPass()
    {
        $data = $this->mockData;
        $data["month"] = "@!$%^";
        $errorTest = (new MocK())->valisus($data);
        $this->assertIsNotBool($errorTest);
        $this->assertIsArray($errorTest);
        $this->assertArrayHasKey('status', $errorTest);
        $this->assertArrayHasKey('message', $errorTest);
        $this->assertEquals($errorTest["message"], "Month should be a number.", "Error message: " . $errorTest["message"]);
        $this->assertEquals($errorTest["status"], "exception");
    }

    public function testWhenMonthValueIsAnAlphabetWillNotPass()
    {
        $data = $this->mockData;
        $data["month"] = "a";
        $errorTest = (new MocK())->valisus($data);
        $this->assertIsNotBool($errorTest);
        $this->assertIsArray($errorTest);
        $this->assertArrayHasKey('status', $errorTest);
        $this->assertArrayHasKey('message', $errorTest);
        $this->assertEquals($errorTest["message"], "Month should be a number.", "Error message: " . $errorTest["message"]);
        $this->assertEquals($errorTest["status"], "exception");
    }

    public function testWhenMonthValueIsWordWillNotPass()
    {
        $data = $this->mockData;
        $data["month"] = "awooga";
        $errorTest = (new MocK())->valisus($data);
        $this->assertIsNotBool($errorTest);
        $this->assertIsArray($errorTest);
        $this->assertArrayHasKey('status', $errorTest);
        $this->assertArrayHasKey('message', $errorTest);
        $this->assertEquals($errorTest["message"], "Month should be a number.", "Error message: " . $errorTest["message"]);
        $this->assertEquals($errorTest["status"], "exception");
    }

    public function testZeroMonthWillNotPass()
    {
        $data = $this->mockData;
        $data["month"] = 0;
        $errorTest = (new Mock())->valisus($data);
        $this->assertIsNotBool($errorTest);
        $this->assertIsArray($errorTest);
        $this->assertArrayHasKey('status', $errorTest);
        $this->assertArrayHasKey('message', $errorTest);
        $this->assertEquals($errorTest["message"], "Month should be a number.", "Error message: " . $errorTest["message"]);
        $this->assertEquals($errorTest["status"], "exception");
    }

    public function testNegativeMonthWillNotPass()
    {
        $data = $this->mockData;
        $data["month"] = -12;
        $errorTest = (new Mock())->valisus($data);
        $this->assertIsNotBool($errorTest);
        $this->assertIsArray($errorTest);
        $this->assertArrayHasKey('status', $errorTest);
        $this->assertArrayHasKey('message', $errorTest);
        $this->assertEquals($errorTest["message"], "Month should be a number ranging from 1 to 12.", "Error message: " . $errorTest["message"]);
        $this->assertEquals($errorTest["status"], "exception");
    }

    public function testMonthBeyond12WillNotPass()
    {
        $data = $this->mockData;
        $data["month"] = 14;
        $errorTest = (new Mock())->valisus($data);
        $this->assertIsNotBool($errorTest);
        $this->assertIsArray($errorTest);
        $this->assertArrayHasKey('status', $errorTest);
        $this->assertArrayHasKey('message', $errorTest);
        $this->assertEquals($errorTest["message"], "Month should be a number ranging from 1 to 12.", "Error message: " . $errorTest["message"]);
        $this->assertEquals($errorTest["status"], "exception");
    }

    public function testMonthIsAnArrayWillNotPass()
    {
        $data = $this->mockData;
        $data["month"] = [13, "11", 1];
        $errorTest = (new Mock())->valisus($data);
        $this->assertIsNotBool($errorTest);
        $this->assertIsArray($errorTest);
        $this->assertArrayHasKey('status', $errorTest);
        $this->assertArrayHasKey('message', $errorTest);
        $this->assertEquals($errorTest["status"], "exception");
    }

    public function testMonthIsAnObjectWillNotPass()
    {
        $data = $this->mockData;
        $data["month"] = new Mock();
        $errorTest = (new Mock())->valisus($data);
        $this->assertIsNotBool($errorTest);
        $this->assertIsArray($errorTest);
        $this->assertArrayHasKey('status', $errorTest);
        $this->assertArrayHasKey('message', $errorTest);
        $this->assertEquals($errorTest["status"], "exception");
    }

    public function testNumericStringValidYearWillReturnTrue()
    {
        $data = $this->mockData;
        $data["year"] = "2011";
        $true = (new Mock())->valisus(($data));
        $this->assertIsBool($true);
        $this->assertTrue($true);
    }

    public function testValidNumericStringYearAppendedWithAndAnAlphabetWillNotPass()
    {
        $data = $this->mockData;
        $data["year"] = "2004a";
        $errorTest = (new Mock())->valisus($data);
        $this->assertIsNotBool($errorTest);
        $this->assertIsArray($errorTest);
        $this->assertArrayHasKey('status', $errorTest);
        $this->assertArrayHasKey('message', $errorTest);
        $this->assertEquals($errorTest["message"], "Year should be a number.", "Error message: " . $errorTest["message"]);
        $this->assertEquals($errorTest["status"], "exception");
    }

    public function test4CharactersLongYearWithAnAlphabetWillNotPass()
    {
        $data = $this->mockData;
        $data["year"] = "200a";
        $errorTest = (new Mock())->valisus($data);
        $this->assertIsNotBool($errorTest);
        $this->assertIsArray($errorTest);
        $this->assertArrayHasKey('status', $errorTest);
        $this->assertArrayHasKey('message', $errorTest);
        $this->assertEquals($errorTest["message"], "Year should be a number.", "Error message: " . $errorTest["message"]);
        $this->assertEquals($errorTest["status"], "exception");
    }

    public function test4CharactersLongYearWithAWordWillNotPass()
    {
        $data = $this->mockData;
        $data["year"] = "2one";
        $errorTest = (new Mock())->valisus($data);
        $this->assertIsNotBool($errorTest);
        $this->assertIsArray($errorTest);
        $this->assertArrayHasKey('status', $errorTest);
        $this->assertArrayHasKey('message', $errorTest);
        $this->assertEquals($errorTest["message"], "Year should be a number.", "Error message: " . $errorTest["message"]);
        $this->assertEquals($errorTest["status"], "exception");
    }

    public function test4CharactersLongYearWithSpecialCharacterWillNotPass()
    {
        $data = $this->mockData;
        $data["year"] = "20!!";
        $errorTest = (new Mock())->valisus($data);
        $this->assertIsNotBool($errorTest);
        $this->assertIsArray($errorTest);
        $this->assertArrayHasKey('status', $errorTest);
        $this->assertArrayHasKey('message', $errorTest);
        $this->assertEquals($errorTest["message"], "Year should be a number.", "Error message: " . $errorTest["message"]);
        $this->assertEquals($errorTest["status"], "exception");
    }

    public function testValidYearBelow2011WillNotPass()
    {
        $data = $this->mockData;
        $data["year"] = 2010;
        $errorTest = (new Mock())->valisus($data);
        $this->assertIsNotBool($errorTest);
        $this->assertIsArray($errorTest);
        $this->assertArrayHasKey('status', $errorTest);
        $this->assertArrayHasKey('message', $errorTest);
        $this->assertEquals($errorTest["message"], "Year falls outside the valid range.", "Error message: " . $errorTest["message"]);
        $this->assertEquals($errorTest["status"], "exception");
    }

    public function testValidYearFromTheFutureWillNotPass()
    {
        $data = $this->mockData;
        $data["year"] = date('Y') + 1;
        $errorTest = (new Mock())->valisus($data);
        $this->assertIsNotBool($errorTest);
        $this->assertIsArray($errorTest);
        $this->assertArrayHasKey('status', $errorTest);
        $this->assertArrayHasKey('message', $errorTest);
        $this->assertEquals($errorTest["message"], "Year falls outside the valid range.", "Error message: " . $errorTest["message"]);
        $this->assertEquals($errorTest["status"], "exception");
    }

    public function testWhenYearIsALiteralWordWillNotPass()
    {
        $data = $this->mockData;
        $data["year"] = "twenty twenty three";
        $errorTest = (new Mock())->valisus($data);
        $this->assertIsNotBool($errorTest);
        $this->assertIsArray($errorTest);
        $this->assertArrayHasKey('status', $errorTest);
        $this->assertArrayHasKey('message', $errorTest);
        $this->assertEquals($errorTest["message"], "Year should be a number.", "Error message: " . $errorTest["message"]);
        $this->assertEquals($errorTest["status"], "exception");
    }

    public function testWhenTheYearIsAnArrayWillNotPass()
    {
        $data = $this->mockData;
        $data["year"] = [2023];
        $errorTest = (new Mock())->valisus($data);
        $this->assertIsNotBool($errorTest);
        $this->assertIsArray($errorTest);
        $this->assertArrayHasKey('status', $errorTest);
        $this->assertArrayHasKey('message', $errorTest);
        $this->assertEquals($errorTest["message"], "Year should be a number.", "Error message: " . $errorTest["message"]);
        $this->assertEquals($errorTest["status"], "exception");
    }

    public function testWhenTheYearIsAnObjectWillNotPass()
    {
        $data = $this->mockData;
        $data["year"] = new MocK();
        $errorTest = (new Mock())->valisus($data);
        $this->assertIsNotBool($errorTest);
        $this->assertIsArray($errorTest);
        $this->assertArrayHasKey('status', $errorTest);
        $this->assertArrayHasKey('message', $errorTest);
        $this->assertEquals($errorTest["message"], "Year should be a number.", "Error message: " . $errorTest["message"]);
        $this->assertEquals($errorTest["status"], "exception");
    }

    public function testWhenTheDeviceIdsIsInvalidArrayWillNotPass()
    {
        $data = $this->mockData;
        $data["devices"] = ["whatthefuck", 12345678];
        $errorTest = (new Mock())->valisus($data);
        $this->assertIsNotBool($errorTest);
        $this->assertIsArray($errorTest);
        $this->assertArrayHasKey('status', $errorTest);
        $this->assertArrayHasKey('message', $errorTest);
        $this->assertEquals($errorTest["status"], "exception");
    }

    public function testWhenTheDeviceIdsIsArrayOfNumbersWillNotPass()
    {
        $data = $this->mockData;
        $data["devices"] = [1234567,123458];
        $errorTest = (new Mock())->valisus($data);
        $this->assertIsNotBool($errorTest);
        $this->assertIsArray($errorTest);
        $this->assertArrayHasKey('status', $errorTest);
        $this->assertArrayHasKey('message', $errorTest);
        $this->assertEquals($errorTest["status"], "exception");
    }

    public function testWhenTheDeviceIdIsNumbersWillNotPass()
    {
        $data = $this->mockData;
        $data["devices"] = 12345678;
        $errorTest = (new Mock())->valisus($data);
        $this->assertIsNotBool($errorTest);
        $this->assertIsArray($errorTest);
        $this->assertArrayHasKey('status', $errorTest);
        $this->assertArrayHasKey('message', $errorTest);
        $this->assertEquals($errorTest["status"], "exception");
    }

    public function testWhenTheDeviceIdIsLessThan7CharactersLongWillNotPass()
    {
        $data = $this->mockData;
        $data["devices"] = "12sdew";
        $errorTest = (new Mock())->valisus($data);
        $this->assertIsNotBool($errorTest);
        $this->assertIsArray($errorTest);
        $this->assertArrayHasKey('status', $errorTest);
        $this->assertArrayHasKey('message', $errorTest);
        $this->assertEquals($errorTest["status"], "exception");
    }

    public function testWhenTheDeviceIdIsMoreThan8CharactersLongWillNotPass()
    {
        $data = $this->mockData;
        $data["devices"] = "12sdewisa";
        $errorTest = (new Mock())->valisus($data);
        $this->assertIsNotBool($errorTest);
        $this->assertIsArray($errorTest);
        $this->assertArrayHasKey('status', $errorTest);
        $this->assertArrayHasKey('message', $errorTest);
        $this->assertEquals($errorTest["status"], "exception");
    }

    public function testWhenTheDeviceIdHasSpecialCharactersWillNotPass()
    {
        $data = $this->mockData;
        $data["devices"] = "1234awe@";
        $errorTest = (new Mock())->valisus($data);
        $this->assertIsNotBool($errorTest);
        $this->assertIsArray($errorTest);
        $this->assertArrayHasKey('status', $errorTest);
        $this->assertArrayHasKey('message', $errorTest);
        $this->assertEquals($errorTest["status"], "exception");
    }

    public function testWhenTheDeviceIdIsSusItsAnObjectWillNotPass()
    {
        $data = $this->mockData;
        $data["devices"] = new Mock();
        $errorTest = (new Mock())->valisus($data);
        $this->assertIsNotBool($errorTest);
        $this->assertIsArray($errorTest);
        $this->assertArrayHasKey('status', $errorTest);
        $this->assertArrayHasKey('message', $errorTest);
        $this->assertEquals($errorTest["status"], "exception");
    }

    public function testWhenTheDeviceIdsIsAnEmptyArrayWillNotPass()
    {
        $data = $this->mockData;
        $data["devices"] = [];
        $errorTest = (new Mock())->valisus($data);
        $this->assertIsNotBool($errorTest);
        $this->assertIsArray($errorTest);
        $this->assertArrayHasKey('status', $errorTest);
        $this->assertArrayHasKey('message', $errorTest);
        $this->assertEquals($errorTest["status"], "exception");
    }

    public function testWhenTheDeviceIdsHasArrayWillNotPass()
    {
        $data = $this->mockData;
        $data["devices"] = [
            "1234567",
            ["troll"]
        ];
        $errorTest = (new Mock())->valisus($data);
        $this->assertIsNotBool($errorTest);
        $this->assertIsArray($errorTest);
        $this->assertArrayHasKey('status', $errorTest);
        $this->assertArrayHasKey('message', $errorTest);
        $this->assertEquals($errorTest["status"], "exception");
    }

    public function testWhenTheDeviceIdIsNullWillNotPass()
    {
        $data = $this->mockData;
        $data["devices"] = NULL;
        $errorTest = (new Mock())->valisus($data);
        $this->assertIsNotBool($errorTest);
        $this->assertIsArray($errorTest);
        $this->assertArrayHasKey('status', $errorTest);
        $this->assertArrayHasKey('message', $errorTest);
        $this->assertEquals($errorTest["status"], "exception");
    }

    public function testWhenTheDeviceIdIsBoolWillNotPass()
    {
        $data = $this->mockData;
        $data["devices"] = false;
        $errorTest = (new Mock())->valisus($data);
        $this->assertIsNotBool($errorTest);
        $this->assertIsArray($errorTest);
        $this->assertArrayHasKey('status', $errorTest);
        $this->assertArrayHasKey('message', $errorTest);
        $this->assertEquals($errorTest["status"], "exception");
    }
}