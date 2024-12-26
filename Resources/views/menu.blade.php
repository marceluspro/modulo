<li>
    <a href="#" class="has-arrow" aria-expanded="false">
        <div class="nav_icon_small">
            <i class="fas fa-gamepad"></i>
        </div>
        <div class="nav_title">
            <span>{{__('setting.User Type')}}</span>
            @if(env('APP_SYNC'))
                <span class="demo_addons">Addon</span>
            @endif
        </div>
    </a>
    <ul>
        @if (permissionCheck('usertype.list'))
            <li>
                <a href="{{route('usertype.list')}}">{{__('common.List')}}</a>
            </li>
        @endif
    </ul>
</li>
