<?php
/**
 * The model file of stat module of ChanzhiEPS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Xiying Guan <guanxiying@xirangit.com>
 * @package     stat
 * @version     $Id$
 * @link        http://www.chanzhi.org
 */
class statModel extends model
{
    /**
     * Get traffic per hour of one day.
     * 
     * @param  int    $day 
     * @access public
     * @return void
     */
    public function getTrafficPerHour($day)
    {
        $traffic = $this->dao->select('*')->from(TABLE_STATREPORT)
            ->where('type')->eq('basic')->andWhere('item')->eq('total')
            ->andWhere('timeType')->eq('hour')->andWhere('timeValue')->like("{$day}%")
            ->fetchAll('timeValue');

        foreach($this->config->stat->hourLabels as $label)
        {
            $hour = date('Ymd') . $label;
            $pv[] = isset($traffic[$hour]) ? $traffic[$hour]->pv : 0;
            $uv[] = isset($traffic[$hour]) ? $traffic[$hour]->uv : 0;
            $ip[] = isset($traffic[$hour]) ? $traffic[$hour]->ip : 0;
        }

        $chartData = array();

        $pvChart = new stdclass(); 
        $pvChart->label = $this->lang->stat->pv;
        $pvChart->color = 'green';
        $pvChart->data  = $pv;

        $uvChart = new stdclass(); 
        $uvChart->label = $this->lang->stat->uv;
        $uvChart->color = 'blue';
        $uvChart->data  = $uv;

        $ipChart = new stdclass(); 
        $ipChart->label = $this->lang->stat->ipCount;
        $ipChart->color = 'red';
        $ipChart->data  = $ip;

        $chartData[] = $pvChart;
        $chartData[] = $uvChart;
        $chartData[] = $ipChart;
        return $chartData;
    }

    /**
     * Get traffic of somedays. 
     * 
     * @param  string    $mode 
     * @access public
     * @return void
     */
    public function getTrafficPerDay($mode)
    {
        
        if($mode == 'weekly')  $days = 7;
        if($mode == 'monthly') $days = 30;
        $begin = date('Ymd', strtotime("-{$days} day"));

        $traffic = $this->dao->select('*')->from(TABLE_STATREPORT)
            ->where('type')->eq('basic')->andWhere('item')->eq('total')
            ->andWhere('timeType')->eq('day')->andWhere('timeValue')->ge($begin)
            ->fetchAll('timeValue');

        for($i = $days; $i > 0;  $i--)
        {
            $day = date('Ymd', strtotime("-{$i} day"));
            $pv[] = isset($traffic[$day]) ? $traffic[$day]->pv : 0;
            $uv[] = isset($traffic[$day]) ? $traffic[$day]->uv : 0;
            $ip[] = isset($traffic[$day]) ? $traffic[$day]->ip : 0;
            $dayLabels[] = $day;
        }

        $chartData = array();

        $pvChart = new stdclass(); 
        $pvChart->label = $this->lang->stat->pv;
        $pvChart->color = 'green';
        $pvChart->data  = $pv;

        $uvChart = new stdclass(); 
        $uvChart->label = $this->lang->stat->uv;
        $uvChart->color = 'blue';
        $uvChart->data  = $uv;

        $ipChart = new stdclass(); 
        $ipChart->label = $this->lang->stat->ipCount;
        $ipChart->color = 'red';
        $ipChart->data  = $ip;

        $chartData[] = $pvChart;
        $chartData[] = $uvChart;
        $chartData[] = $ipChart;
        return array('labels' => $dayLabels, 'chartData' => $chartData);
    }
}