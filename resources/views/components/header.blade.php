@php use App\Models\System; @endphp
<div class="header table-header layui-row">
    <div class="header-title"><a href="/"
                                 class="web-titles"><span>{{System::getSystem("system","name","控制台")}}</span></a>
    </div>
    <div class="header-users">
        <div></div>
        <div></div>
        <div style="height: 100%;">
            <a href="javascript:" to-url="?m=admin.show" class="user-image items" id="header-user-image">
                <img width="24px" height="24px" src="/favicon.ico">
                <p style="margin-right: 10px;margin-left: 5px;">{{$user->user}}</p>
            </a>
        </div>
    </div>
</div>
