<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="#" class="brand-link">
    <?php if(file_exists('images/logo/'.$website_info->logo)){ ?>
    <img src="{{  asset('images/') }}/logo/{{ $website_info->logo }}" alt="{{ $website_info->name }}" class="brand-image img-circle elevation-3" style="opacity: 1;height: 30px;width: 30px">
    <?php } ?>
    <span class="brand-text font-weight-light">{{ Str::upper(Str::limit($website_info->name, 2, '')) }}</span>

  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="{{ auth()->user()->img ? asset('images/employees/' . auth()->user()->img) : asset('images/default.png') }}" height="60" width="60" alt="Employee Image">

      </div>
      <div class="info">
        <a href="{{ route('profile') }}" class="d-block">{{ Auth::user()->name }}</a>
      </div>
    </div>

    <!-- SidebarSearch Form -->
    <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
            <input 
                id="sidebar-search" 
                class="form-control form-control-sidebar" 
                type="search" 
                placeholder="Search" 
                aria-label="Search" 
                oninput="filterSidebarMenu()"
            >
            <div class="input-group-append">
                <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul id="sidebar-menu" class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            @php
            $menus = App\Models\Menu::with('children')->orderBy('order', 'asc')->get();
            @endphp

            @foreach ($menus as $item)
                @if (!$item->parent_id && $item->can_view)
                    <li class="nav-item has-treeview">
                        <a href="{{ Route::has($item->route) ? route($item->route) : 'javascript:void(0)' }}" class="nav-link">
                            <i class="nav-icon {{ $item->icon }}"></i>
                            <p>
                                {{ $item->name }}
                                @if($item->children->isNotEmpty())
                                    <i class="right fas fa-angle-left"></i>
                                @endif
                            </p>
                        </a>

                        @if($item->children->isNotEmpty())
                            <ul class="nav nav-treeview">
                                @foreach ($item->children as $child)
                                    @if($child->can_view)
                                        <li class="nav-item">
                                            <a href="{{ Route::has($child->route) ? route($child->route) : 'javascript:void(0)' }}" class="nav-link">
                                                <i class="nav-icon {{ $child->icon }}"></i>
                                                <p>{{ $child->name }}</p>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endif
            @endforeach
        </ul>
    </nav>


  </div>
  <!-- /.sidebar -->
</aside>