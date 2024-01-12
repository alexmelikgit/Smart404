<?php
    $settings = new Sm404settings();
?>
<div class="container sm404-settings-page">
    <h1 class="title">
        <?=__("Smart 404 Settings", "smart404")?>
    </h1>
    <div class="graphics-container">
        <div class="auto-redirect-switcher">
            <h3><?=__("Auto Redirect", "smart404")?></h3>
            <div class="switcher-wrapper">
                <span><?=__("No", "smart404")?></span>
                <div class="switcher <?=get_option("sm404_autoredirect") == "1" ? "active" : ""?>"></div>
                <span><?=__("Yes", "smart404")?></span>
            </div>
        </div>
    </div>
    <?php if(get_option("sm404_autoredirect") === "1" ) :?>
    <div class="icon-block">
        <div class="icon-item">
            <i class="fa fa-home"></i>
        </div>
        <div class="icon-text">
            <h5 class="title-item"><?=__("Post Types", "smart404")?></h5>
            <span class="text-item"><?=__("Select the post types for which you want to enable Auto Redirect", "smart404")?></span>
        </div>
    </div>
    <div class="graphics-container">
        <div class="panel-wrapper graphics-wrapper">
            <table class="panel-item" id="post_type">
                <thead>
                    <tr>
                        <th><?=__("Post Type", "smart404")?></th>
                        <th><span class="panel-btn"><?=__("Searchable", "smart404")?></span></th>
                        <th><button class="panel-btn btn update-btn"><?=__("Update", "smart404")?></button></th>
                    </tr>
                </thead>
                    <tbody>
                        <?php $actives = Sm404settings::get_active_post_tpes();foreach ($settings->get_post_types() as $post_type) : ?>
                            <tr>
                                <td data-key><?=$post_type?></td>
                                <td><div class="switcher-wrapper"><?=__("No", "smart404")?><div class="switcher <?=in_array($post_type, $actives) ? "active" : ""?>"></div><?=__("Yes", "smart404")?></div></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
            </table>
            <div class="popup-body">
                <div class="popup-item">
                    <form action="#" method="POST" class="redirects-form">
                        <label for="url">
                            <span><?=__("404 URL", "smart404")?></span>
                            <input id="url" type="text" name="url" id="" >
                        </label>
                        <label for="redirect">
                            <span><?=__("Redirect", "smart404")?></span>
                            <input type="text" name="redirect" id="redirect" data-url>
                        </label>
                        <input type="text" class="hidden" value="post_type" name="type">
                        <button class="btn"><?=__("Add", "smart404")?> <i class="fa fa-plus"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endif ; ?>
</div>