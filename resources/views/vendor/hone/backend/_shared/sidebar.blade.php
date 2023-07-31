@php
    $project_content_globs = $project_content_globs ?? [];
@endphp
<nav class="main-nav">

    <ul class="sidebar-nav">
        <li><a class="{{ CMS::current(['admin.dashboard']) }}" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
        <li class="has-sub {{ CMS::current(array_merge([
           'pages.*',
           'media.*',
           'news-articles.*',
           'sliders.*',
           'slides.*',
           'menus.*',
           'snippets.*',
           ], $project_content_globs)) }}">
            <a href="#">Content</a>
            <ul>
                <li><a class="{{ CMS::current(['categories.*']) }}" href="{{ route('backend.categories.index') }}">Categories</a></li>
                <li><a class="{{ CMS::current(['pages.*']) }}" href="{{ route('pages.index') }}">Site Pages</a></li>
                <li><a class="{{ CMS::current(['media.*']) }}" href="{{ route('media.index') }}">Media Library</a></li>
                <li><a class="{{ CMS::current(['news-articles.*']) }}" href="{{ route('news-articles.index') }}">News Articles</a></li>
                <li><a class="{{ CMS::current(['promotions.*']) }}" href="{{ route('backend.promotions.index') }}">Promotions</a></li>
                <li><a class="{{ CMS::current(['sliders.*']) }}" href="{{ route('sliders.index') }}">Sliders</a></li>
                <li><a class="{{ CMS::current(['menus.*']) }}" href="{{ route('menus.index') }}">Site Menus</a></li>
                <li><a class="{{ CMS::current(['snippets.*']) }}" href="{{ route('snippets.index') }}">Snippets</a></li>

                @yield('project_content_links')
            </ul>
        </li>
        <li><a class="{{ CMS::current(['members.*']) }}" href="{{ route('members.index') }}">Members</a></li>
    </ul>

    @yield('project_menu')

    <ul class="sidebar-nav">
        <li class="has-sub {{ CMS::current([
          'cropper-presets.*',
          'ips.*',
          'users.*',
          'settings.*',
          'bin.*',
          'notices.*'
       ]) }}">
            <a href="#">Admin</a>
            <ul>
                <li><a class="{{ CMS::current(['cropper-presets.*']) }}" href="{{ route('cropper-presets.index') }}">Cropper presets</a></li>

                @if (CMS::getUser()->can('is_smith'))
                <li><a class="{{ CMS::current(['ips.*']) }}" href="{{ route('ips.index') }}">IP Whitelist</a></li>
                @endif

                <li><a class="{{ CMS::current(['notices.*', 'notices.edit']) }}" href="{{ route('notices.index') }}">Notices</a></li>

                @if (CMS::getUser()->can('recycle'))
                <li><a class="{{ CMS::current(['bin.*']) }}" href="{{ route('bin.index') }}">Recycle Bin</a></li>
                @endif

                @if (CMS::getUser()->can('manage_settings'))
                <li><a class="{{ CMS::current(['settings.*']) }}" href="{{ route('settings.index') }}">Settings</a></li>
                @endif

                <li><a class="{{ CMS::current(['users.*']) }}" href="{{ route('users.index') }}">Users</a></li>
            </ul>
        </li>
        <li><a href="{{ route('admin.logout') }}">Log out</a></li>
    </ul>
</nav>
