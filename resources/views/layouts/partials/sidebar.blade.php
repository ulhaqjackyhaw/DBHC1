<aside
    class="d-flex flex-column bg-slate-800 text-white shadow-lg position-fixed h-100 z-50 transition-transform duration-300 ease-in-out"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" style="width: 260px; left:0; top:0;">

    {{-- Header --}}
    <div class="d-flex align-items-center p-4 border-bottom border-slate-700" style="min-height: 64px;">
        <i class="bi bi-bar-chart-line-fill text-2xl me-3 text-sky-400"></i>
        <h2 class="h5 mb-0 font-semibold tracking-wider">Analitik Data</h2>
    </div>

    {{-- Navigation --}}
    <nav class="flex-grow-1 p-3 sidebar-scroll overflow-y-auto">
        <ul class="nav flex-column gap-2">

            {{-- Dashboard Utama --}}
            <li class="nav-item">
                <a href="{{ route('dashboard.index') }}"
                    class="nav-link d-flex align-items-center rounded-lg px-3 py-2 {{ request()->routeIs('dashboard.index') ? 'text-white bg-slate-900/50' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="bi bi-grid-1x2-fill me-3 w-5"></i> Dashboard Utama
                </a>
            </li>

            {{-- Data Karyawan --}}
            <li class="nav-item">
                <a href="{{ route('karyawan.index') }}"
                    class="nav-link d-flex align-items-center rounded-lg px-3 py-2 {{ request()->routeIs('karyawan.index') ? 'text-white bg-slate-900/50' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="bi bi-people-fill me-3 w-5"></i> Data Karyawan
                </a>
            </li>

            {{-- Analitik Karyawan Organik --}}
            <li class="nav-item">
                <a href="{{ route('analitik.organic') }}"
                    class="nav-link d-flex align-items-center rounded-lg px-3 py-2 {{ request()->routeIs('analitik.organic') ? 'text-white bg-slate-900/50' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="bi bi-person-badge-fill me-3 w-5"></i> Analitik Karyawan Organik
                </a>
            </li>

            {{-- Analitik Karyawan Outsourcing --}}
            <li class="nav-item">
                <a href="{{ route('analitik.outsourcing') }}"
                    class="nav-link d-flex align-items-center rounded-lg px-3 py-2 {{ request()->routeIs('analitik.outsourcing') ? 'text-white bg-slate-900/50' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="bi bi-person-workspace me-3 w-5"></i> Analitik Karyawan Outsourcing
                </a>
            </li>

            {{-- Data Formasi --}}
            <li class="nav-item">
                <a href="{{ route('formasi.index') }}"
                    class="nav-link d-flex align-items-center rounded-lg px-3 py-2 {{ request()->routeIs('formasi.*') ? 'text-white bg-slate-900/50' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="bi bi-building-fill me-3 w-5"></i> Data Formasi
                </a>
            </li>



            {{-- Versions --}}
            <li class="nav-item">
                <a href="{{ route('versions.index') }}"
                    class="nav-link d-flex align-items-center rounded-lg px-3 py-2 {{ request()->routeIs('versions.*') ? 'text-white bg-slate-900/50' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }}">
                    <i class="bi bi-archive-fill me-3 w-5"></i> Versions
                </a>
            </li>

            {{-- Tambahkan menu lain di sini dengan pola yang sama --}}
        </ul>
    </nav>

    {{-- Footer / Pengaturan --}}
    <div class="p-4 border-top border-slate-700">
        <a href="#"
            class="nav-link d-flex align-items-center text-slate-300 hover:bg-slate-700 hover:text-white rounded-lg px-3 py-2">
            <i class="bi bi-gear-fill me-3 w-5"></i> Pengaturan
        </a>
    </div>
</aside>
