<?php
    $sm404 = new Smart404();
    $sm404->set_scripts();
?>
<div class="container container-main">
    <h1>Smart 404</h1>
    <div class="wrapper">
        <div class="top-bar-wrapper">
            <div class="icon-block">
                <div class="icon-item">
                    <i class="far fa-home"></i>
                </div>
                <div class="icon-text">
                    <h5 class="title-item">404 Statistic's</h5>
                    <span class="text-item">The chart shows statistics filtered for specific insights.</span>
                </div>
            </div>
        </div>

        <div class="graphics-container flex-wrap">
            <div class="graphics-wrapper loading">
                <div class="top-wrapper">
                    <div class="next-back-wrapper">
                        <button class="btn back">Back</button>
                        <button class="btn next">Next</button>
                    </div>
                    <div class="month-week-switcher switcher-wrapper">
                        <span>Month</span>
                        <button class="week-btn switcher"></button>
                        <span>Week</span>
                    </div>
                    <div class="month-picker">
                        <input type="month" class="month-filter">
                        <button class="btn filter-reset">Reset</button>
                    </div>
                </div>

                <canvas class="graphics-item"></canvas>
                <div class="lds-dual-ring"></div>
            </div>
            <div class="totals-wrapper">
                <div class="total-pages total-item">
                    <div class="total-wrapper">
                        <span class="result-title">
                            Redirected Pages Count
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
                            Redirects count
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
        <div class="icon-block">
            <div class="icon-item">
                <i class="far fa-route"></i>
            </div>
            <div class="icon-text">
                <h5 class="title-item">Auto Redirects</h5>
                <span class="text-item">Automatically routes users to alternative pages or URLs, enhancing navigation and user experience.</span>
            </div>
        </div>

            <div class="graphics-container">
                <div class="panel-wrapper graphics-wrapper">
                   <table class="panel-item" id="auto_redirects">
                       <thead>
                           <tr>
                               <th>404 url</th>
                               <th>Redirect url</th>
                               <th>Total redirects</th>
                               <th></th>
                           </tr>
                       </thead>
                       <?php $autoRedirects = new AutoRedirects(); if(is_array($redirects = $autoRedirects->getRedrects())) :?>
                       <tbody>
                            <?php foreach ($redirects as $redirect) : ?>
                            <tr class="<?=$redirect["auto_redirect"] == 0 ? "disabled \" title='Auto redirect is disabled because you added a custom redirect'" : ""?>">
                                <td><?=$redirect["404"]?></td>
                                <td><?=$redirect["redirect"]?></td>
                                <td><?=$redirect["total_autoRedirects"]?></td>
                                <td><button class="btn edit-btn panel-btn pop-open" data-404="<?=$redirect["404"]?>">Add Custom Redirect</button></td>
                            </tr>
                            <?php endforeach; ?>
                       </tbody>
                       <?php endif ; ?>

                   </table>
                </div>

            </div>
        <div class="icon-block">
            <div class="icon-item">
                <i class="far fa-route"></i>
            </div>
            <div class="icon-text">
                <h5 class="title-item">Custom Redirects</h5>
                <span class="text-item">Redirects Initiated by admin</span>
            </div>
        </div>

        <div class="graphics-container">
            <div class="panel-wrapper graphics-wrapper">
                <table class="panel-item" id="custom_redirects">
                    <thead>
                    <tr>
                        <th>404 url</th>
                        <th>Redirect url</th>
                        <th>Total redirects</th>
                        <th><button class="panel-btn btn btn-blue pop-open">Add New Redirect <i class="fa fa-plus"></i></button></th>
                    </tr>
                    </thead>
                    <?php if(is_array($redirects = CustomRedrects::getRedirects())) :?>
                        <tbody>
                            <?php foreach ($redirects as $redirect) : ?>
                                <tr>
                                    <td><?=$redirect["404"]?></td>
                                    <td><?=$redirect["redirect"]?></td>
                                    <td><?=$redirect["total_customRedirects"]?></td>
                                    <td>
                                        <div class="custom_redirect-btns">
                                            <button class="btn pop-open" data-404="<?=$redirect["404"]?>" data-redirect="<?=$redirect["redirect"]?>">Edit</button>
                                            <button class="btn remove-btn">Remove Redirect</button>
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
                <h5 class="title-item">404 Watchlist: Redirect Targets</h5>
                <span class="text-item">Tracks URLs that yield 404 errors, indicating the need for redirection to relevant pages.</span>
            </div>
        </div>
        <div class="graphics-container needing-container">
            <div class="needing-wrapper graphics-wrapper">
                    <table class="panel-item" id="404">
                        <thead>
                            <tr>
                                <th>404 URL</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $needles = $sm404->get_needle() ; if(is_array($needles) ):?>
                        <?php foreach($needles as $needle) : ?>
                            <tr>
                                <td><?=$needle["404"]?></td>
                                <td><?=$needle["total"]?></td>
                                <td><button class="btn pop-open panel-btn" data-404="<?=$needle["404"]?>">Add Redirect</button></td>
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
                            <span>404 URL</span>
                            <input id="url" type="text" name="url" id="" data-url>
                        </label>
                        <label for="redirect">
                            <span>Redirect</span>
                            <input type="text" name="redirect" id="redirect" data-url>
                        </label>
                        <button class="btn">Add <i class="fa fa-plus"></i></button>
                    </form>
                </div>
        </div>
    </div>
</div>
