{*php*}
/**
 * The page list front view file of block module of chanzhiEPS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPLV12 (http://zpl.pub/page/zplv12.html)
 * @author      Tingting Dai <daitingting@xirangit.com>
 * @package     block
 * @version     $Id$
 * @link        http://www.chanzhi.org
*/
{*/php*}
{*php*}
/* Decode the content and get pages. */
$content = json_decode($block->content);
$pages   = $control->loadModel('article')->getPageList($content->limit);
{*/php*}
<div id="block{!echo $block->id}" class='panel panel-block {!echo $blockClass}'>
  <div class='panel-heading'>
    <strong>{!echo $icon . $block->title}</strong>
    {if(!empty($content->moreText) and !empty($content->moreUrl))}
    <div class='pull-right'>{!echo html::a($content->moreUrl, $content->moreText)}</div>
    {/if}
  </div>
  {if(isset($content->image))}
  <div class='panel-body no-padding'>
    <div class='cards condensed cards-list'>
{*php*}
    foreach($pages as $page):
    $url = helper::createLink('page', 'view', "id=$page->id", "name=$page->alias");
{*/php*}
    <a class='card' href='{!echo $url ?>'>
      <div class='card-heading' style='color:{!echo $page->titleColor}'><strong>{!echo $page->title}</strong></div>
      <div class='table-layout'>
        <div class='table-cell'>
          <div class='card-content text-muted small'>
            <strong class='text-important'>{if(isset($content->time)) echo "<i class='icon-time'></i> " . formatTime($page->addedDate, DT_DATE4)}</strong> &nbsp;{!echo $page->summary}
          </div>
        </div>
        {if(!empty($page->image))}
        <div class='table-cell thumbnail-cell'>
{*php*}
          $title = $page->image->primary->title ? $page->image->primary->title : $page->title;
          echo html::image($control->loadModel('file')->printFileURL($page->image->primary->pathname, $page->image->primary->extension, 'article', 'smallURL'), "title='{$title}' class='thumbnail'" );
{*/php*}
        </div>
        {/if}
      </div>
    </a>
    {/foreach}
    </div>
  </div>
  {else}
  <div class='panel-body no-padding'>
    <div class='list-group simple'>
      {foreach($pages as $page)}
      {$url = helper::createLink('page', 'view', "id={$page->id}", "name={$page->alias}")}
      {if(isset($content->time))}
      <div class='list-group-item'>
        {!echo html::a($url, $page->title, "title='{$page->title}' style='color:{$page->titleColor}'")}
        <span class='pull-right text-muted'>{!echo substr($page->addedDate, 0, 10)}</span>
      </div>
      {else}
      <div class='list-group-item'>{!echo html::a($url, $page->title, "title='{$page->title}' style='color:{$page->titleColor}'")}</div>
      {/if}
      
      {/foreach}
    </div>
  </div>
  {/if}
</div>
