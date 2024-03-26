<?php
    global $sm404;
?>
<div class="container container-main">
    <h1><?=__("Smart 404", "smart404")?></h1>
    <div class="wrapper">
        <div class="top-bar-wrapper">
            <div class="icon-block">
                <div class="icon-item">
                    <i class="far fa-home"></i>
                </div>
                <div class="icon-text">
                    <h5 class="title-item"><?=__("404 Statistic's", "smart404")?></h5>
                    <span class="text-item"><?=__("The chart shows statistics filtered for specific insights.", "smart404")?></span>
                </div>
            </div>
        </div>

        <div class="graphics-container flex-wrap">
            <div class="graphics-wrapper loading">
                <div class="top-wrapper">
                    <div class="next-back-wrapper">
                        <button class="btn back"><?=__("Back", "smart404")?></button>
                        <button class="btn next"><?=__("Next", "smart404")?></button>
                    </div>
                    <div class="month-week-switcher switcher-wrapper">
                        <span><?=__("Month", "smart404")?></span>
                        <button class="week-btn switcher"></button>
                        <span><?=__("Week", "smart404")?></span>
                    </div>
                    <div class="month-picker">
                        <input type="month" class="month-filter">
                        <button class="btn filter-reset"><?=__("Reset", "smart404")?></button>
                    </div>
                </div>

                <canvas class="graphics-item"></canvas>
                <div class="lds-dual-ring"></div>
            </div>
            <div class="totals-wrapper">
                <div class="total-pages total-item">
                    <div class="total-wrapper">
                        <span class="result-title">
                            <?=__("Redirected Pages Count", "smart404")?>
                        </span>
                        <span class="total-result"><?=$sm404->get_total_pages()?></span>
                    </div>
                    <div class="result-icon">
                        <div class="icon-block">
                            <div class="icon-item">
                                <i class="fa fa-exclamation-triangle"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="total-redirects total-item">
                    <div class="total-wrapper">
                        <span class="result-title">
                            <?=__("Redirects count", "smart404")?>
                        </span>
                        <span class="total-result"><?=$sm404->get_total_redirects()?></span>
                    </div>
                    <div class="result-icon">
                        <div class="icon-block">
                            <div class="icon-item">
                                <i class="fa fa-route"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if(get_option("sm404_autoredirect") === "1") : ?>
        <div class="icon-block">
            <a href="<?=home_url("wp-admin/admin.php?page=s404&subpage=auto_redirects")?>" class="icon-item">
                <i class="far fa-route"></i>
            </a>
            <a href="<?=home_url("wp-admin/admin.php?page=s404&subpage=auto_redirects")?>" class="icon-text">
                <h5 class="title-item"><?=__("Auto Redirects", "smart404")?></h5>
                <span class="text-item"><?=__("Automatically routes users to alternative pages or URLs, enhancing navigation and user experience.", "smart404")?></span>
            </a>
        </div>

            <div class="graphics-container">
                <div class="panel-wrapper graphics-wrapper">
                   <table class="panel-item" id="auto_redirects">
                       <thead>
                           <tr>
                               <th><?=__("404 url", "smart404")?></th>
                               <th><?=__("Redirect url", "smart404")?></th>
                               <th><?=__("Total redirects", "smart404")?></th>
                               <th data-orderable="false"></th>
                           </tr>
                       </thead>
                       <?php $autoRedirects = new AutoRedirects(); if(is_array($redirects = $autoRedirects->getRedrects())) : ?>
                       <tbody>
                            <?php foreach ($redirects as $redirect) : ?>
                            <tr class="<?=$redirect["redirect"] != NULL  ? "disabled \" title='Auto redirect is disabled because you added a custom redirect'" : ""?>">
                                <td><?=urldecode($redirect["404"])?></td>
                                <td><?=urldecode($redirect["auto_redirect"])?></td>
                                <td><?=$redirect["total_autoRedirects"]?></td>
                                <td><button class="btn edit-btn panel-btn pop-open" data-404="<?=$redirect["404"]?>"><?=__("Add Custom Redirect", "smart404")?></button></td>
                            </tr>
                            <?php endforeach; ?>
                       </tbody>
                       <?php endif ; ?>

                   </table>
                </div>

            </div>
        <?php endif ;?>
        <div class="icon-block">
            <a href="<?=home_url("wp-admin/admin.php?page=s404&subpage=custom_redirects")?>" class="icon-item" >
                <i class="far fa-route"></i>
            </a>
            <a href="<?=home_url("wp-admin/admin.php?page=s404&subpage=custom_redirects")?>" class="icon-text">
                <h5 class="title-item"><?=__("Custom Redirects", "smart404")?></h5>
                <span class="text-item"><?=__("Redirects Initiated by admin", "smart404")?></span>
            </a>
        </div>

        <div class="graphics-container">
            <div class="panel-wrapper graphics-wrapper">
                <table class="panel-item" id="custom_redirects">
                    <thead>
                    <tr>
                        <th><?=__("404 url", "smart404")?></th>
                        <th><?=__("Redirect url", "smart404")?></th>
                        <th><?=__("Total redirects", "smart404")?></th>
                        <th data-orderable="false"><button class="panel-btn btn btn-blue pop-open"><?=__("Add New Redirect", "smart404")?> <i class="fa fa-plus"></i></button></th>
                    </tr>
                    </thead>
                    <?php if(is_array($redirects = CustomRedrects::getRedirects())) :;?>
                        <tbody>
                            <?php foreach ($redirects as $redirect) : ?>
                                <tr>
                                    <td><?=urldecode($redirect["404"])?></td>
                                    <td><?=urldecode($redirect["redirect"])?></td>
                                    <td><?=$redirect["total_customRedirects"]?></td>
                                    <td>
                                        <div class="custom_redirect-btns">
                                            <button class="btn pop-open" data-404="<?=$redirect["404"]?>" data-redirect="<?=$redirect["redirect"]?>"><?=__("Edit", "smart404")?></button>
                                            <button class="btn remove-btn"><?=__("Remove Redirect", "smart404")?></button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    <?php endif ; ?>

                </table>
            </div>

        </div>
        <div class="icon-block">
            <div class="icon-item">
                <i class="far fa-exclamation-triangle"></i>
            </div>
            <div class="icon-text">
                <h5 class="title-item"><?=__("404 Watchlist: Redirect Targets", "smart404")?></h5>
                <span class="text-item"><?=__("Tracks URLs that yield 404 errors, indicating the need for redirection to relevant pages.", "smart404")?></span>
            </div>
        </div>
        <div class="graphics-container needing-container">
            <div class="needing-wrapper graphics-wrapper">
                    <table class="panel-item" id="404">
                        <thead>
                            <tr>
                                <th><?=__("404 URL", "smart404")?></th>
                                <th><?=__("Total", "smart404")?></th>
                                <th data-orderable="false"></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $needles = $sm404->get_needle() ; if(is_array($needles) ):?>
                        <?php foreach($needles as $needle) :?>
                            <tr class="<?=$needle["disabled"] ? "disabled" : ""?>">
                                <td><?=urldecode($needle["404"])?></td>
                                <td><?=$needle["total"]?></td>
                                <td><button class="btn pop-open panel-btn" data-404="<?=$needle["404"]?>"><?=__("Add Redirect", "smart404")?></button></td>
                            </tr>
                        <?php endforeach; endif; ?>
                        </tbody>
                    </table>
            </div>
        </div>
        <div class="popup-body">
                <div class="popup-item">
                    <form action="#" method="POST" class="redirects-form">
                        <span class="error-message"></span>
                        <label for="url">
                            <span><?=__("404 URL", "smart404")?></span>
                            <input id="url" type="text" name="url" id="" data-url>
                        </label>
                        <label for="redirect">
                            <span><?=__("Redirect", "smart404")?></span>
                            <input type="text" name="redirect" id="redirect" data-url>
                        </label>
                        <label for="redirectType">
                            <span><?=__("Redirect Type", "smart404")?></span>
                            <select name="redirect_type" id="redirectType">
                                <option value="301"><?="301 " . __("Moved Permanently", "smart404")?></option>
                                <option value="302"><?="302 " . __("Found", "smart404")?></option>
                                <option value="307"><?="307 " . __("Temporary Redirect", "smart404")?></option>
                                <option value="410"><?="410 " . __("Content Deleted", "smart404")?></option>
                                <option value="451"><?="451 " . __("Unavailable for Legal Reasons", "smart404")?></option>
                            </select>
                        </label>
                        <button class="btn"><?=__("Add", "smart404")?> <i class="fa fa-plus"></i></button>
                    </form>
                </div>
        </div>
    </div>
</div>
