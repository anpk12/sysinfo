<?php

namespace Anpk12\Sysinfo;

include __DIR__ . "/../../src/Sysinfo/Snapshot.php";

class SnapshotTest extends \PHPUnit_Framework_TestCase
{

    public function testLoadAvg()
    {
        $snapshot = new Snapshot(__DIR__ . '/fakeproc');
        $load = $snapshot->loadavg();
        $count = count($load);
        $this->assertEquals($count, 3);
        $this->assertEquals($load[0], 0.0);
        $this->assertEquals($load[1], 0.01);
        $this->assertEquals($load[2], 0.05);
    }

    public function testMeminfo()
    {
        $snapshot = new Snapshot(__DIR__ . '/fakeproc');
        $meminfo = $snapshot->meminfo();

        $count = count($meminfo);
        $this->assertEquals($count, 43);

        // vim command to generate (most of) the following code from
        // a /proc/meminfo snapshot:
        // :32,74 s/\([a-zA-Z()_0-9]*\):\s*\([0-9]\{1,\}\) kB/$this->assertEquals($meminfo\['\1'\]\['value'], \2);/gc
        $this->assertEquals($meminfo['MemTotal']['value'], 4050068);
        $this->assertEquals($meminfo['MemFree']['value'], 1381148);
        $this->assertEquals($meminfo['MemAvailable']['value'], 2800948);
        $this->assertEquals($meminfo['Buffers']['value'], 412508);
        $this->assertEquals($meminfo['Cached']['value'], 968388);
        $this->assertEquals($meminfo['SwapCached']['value'], 0);
        $this->assertEquals($meminfo['Active']['value'], 1966616);
        $this->assertEquals($meminfo['Inactive']['value'], 473820);
        $this->assertEquals($meminfo['Active(anon)']['value'], 1075384);
        $this->assertEquals($meminfo['Inactive(anon)']['value'], 53592);
        $this->assertEquals($meminfo['Active(file)']['value'], 891232);
        $this->assertEquals($meminfo['Inactive(file)']['value'], 420228);
        $this->assertEquals($meminfo['Unevictable']['value'], 32);
        $this->assertEquals($meminfo['Mlocked']['value'], 32);
        $this->assertEquals($meminfo['SwapTotal']['value'], 7911976);
        $this->assertEquals($meminfo['SwapFree']['value'], 7911976);
        $this->assertEquals($meminfo['Dirty']['value'], 56);
        $this->assertEquals($meminfo['Writeback']['value'], 0);
        $this->assertEquals($meminfo['AnonPages']['value'], 1059464);
        $this->assertEquals($meminfo['Mapped']['value'], 196964);
        $this->assertEquals($meminfo['Shmem']['value'], 69444);
        $this->assertEquals($meminfo['Slab']['value'], 168280);
        $this->assertEquals($meminfo['SReclaimable']['value'], 138088);
        $this->assertEquals($meminfo['SUnreclaim']['value'], 30192);
        $this->assertEquals($meminfo['KernelStack']['value'], 4528);
        $this->assertEquals($meminfo['PageTables']['value'], 20768);
        $this->assertEquals($meminfo['NFS_Unstable']['value'], 0);
        $this->assertEquals($meminfo['Bounce']['value'], 0);
        $this->assertEquals($meminfo['WritebackTmp']['value'], 0);
        $this->assertEquals($meminfo['CommitLimit']['value'], 9937008);
        $this->assertEquals($meminfo['Committed_AS']['value'], 2833516);
        $this->assertEquals($meminfo['VmallocTotal']['value'], 34359738367);
        $this->assertEquals($meminfo['VmallocUsed']['value'], 111084);
        $this->assertEquals($meminfo['VmallocChunk']['value'], 34359537660);
        $this->assertEquals($meminfo['HardwareCorrupted']['value'], 0);
        $this->assertEquals($meminfo['AnonHugePages']['value'], 0);
        $this->assertEquals($meminfo['HugePages_Total']['value'], 0);
        $this->assertEquals($meminfo['HugePages_Free']['value'], 0);
        $this->assertEquals($meminfo['HugePages_Rsvd']['value'], 0);
        $this->assertEquals($meminfo['HugePages_Surp']['value'], 0);
        $this->assertEquals($meminfo['Hugepagesize']['value'], 2048);
        $this->assertEquals($meminfo['DirectMap4k']['value'], 91008);
        $this->assertEquals($meminfo['DirectMap2M']['value'], 4102144);
    }

    public function testHtmlReport()
    {
        $snapshot = new Snapshot(__DIR__ . '/fakeproc');
        $html = $snapshot->htmlReport(false);

        $pos = strpos($html, '<table>');
        $this->assertNotFalse($pos);

        $pos = strpos($html, '</table>');
        $this->assertNotFalse($pos);

        $numTrOpen = substr_count($html, '<tr>');
        $numTrClose = substr_count($html, '</tr>');
        $this->assertEquals($numTrOpen, $numTrClose);
        $this->assertEquals($numTrOpen, 43);

        $numTdOpen = substr_count($html, '<td class="sysinfo_key">')
                   + substr_count($html, '<td class="sysinfo_value">')
                   + substr_count($html, '<td class="sysinfo_unit">');
        $numTdClose = substr_count($html, '</td>');
        $this->assertEquals($numTdOpen, $numTdClose);

        $htmlSummary = $snapshot->htmlReport(true);
        $pos = strpos($htmlSummary, '<p><strong>');
        $this->assertNotFalse($pos);

        $pos = strpos($htmlSummary, '</strong></p>');
        $this->assertNotFalse($pos);
    }
}

?>
