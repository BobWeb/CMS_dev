<style>
    .admin_panel{
        position:absolute;
        top:100px;left:0px;
    }
    .admin_panel .admin_actions{
        max-width:200px;max-height:200px;
        font-size:10px;
        overflow:auto;
        padding:10px;
        float:left;
        display:inline-block;
        z-index:101;
        border:solid 3px #CC042D;
        background:#FFF;
    }
    .admin_panel .admin_call_actions{
        width:20px;height:20px;
        background:#CC042D url('img/admin_panel_ico.png') no-repeat top right;
        float:left;
        display:inline-block;
        margin-left:-10px;
        border:solid 8px #CC042D;
        border-top-right-radius:30px;border-bottom-right-radius:30px;
        border-top-left-radius:0;border-bottom-left-radius:0;
        z-index:100;
    }
    .admin_panel .admin_actions .admin_panel_title{
        margin:0 0 10px;
        color:#CC042D;
        font-size:14px;
        font-weight:bold;
        text-align:center;
    }
    .admin_panel .admin_actions .admin_panel_action{
        width:90px;
        float:left;
        margin:0 0 10px 10px;
        text-align:center;
    }
</style>

<div class="admin_panel">
    <div class="admin_actions">
        <div class="admin_panel_title">Administration</div>
        <div class="admin_panel_action">
            <img src="img/admin_manage.png" /><br />
            Administrateurs
        </div>
        <div class="admin_panel_action">
            <img src="img/page_manage.png" /><br />
            Page
        </div>
        <div class="admin_panel_action">
            <img src="img/content_manage.png" /><br />
            Contenu
        </div>
        <div class="admin_panel_action">
            <img src="img/upload_manage.png" /><br />
            Uploader<br />une image
        </div>
        <div class="clearboth"></div>
    </div>
    <div class="admin_call_actions"></div>
    <div class="clearboth"></div>
</div>