<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>{{ config('app.name') }} | Admin</title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@600;700&display=swap" rel="stylesheet">

	@livewireStyles
	@vite(['resources/css/lux.css'])
</head>
<body class="font-body">
	<div
		x-data="{
			sidebarCollapsed: false,
			init() {
				this.sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true'
			},
			toggleSidebar() {
				this.sidebarCollapsed = !this.sidebarCollapsed
				localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed)
			}
		}"
		class="flex"
	>
		<div
			x-data
			class="fixed flex flex-col w-64 h-screen bg-stone-100 border-r border-stone-200 transition-all duration-500"
			x-cloak
			x-bind:class="{
				'-left-64 opacity-0': sidebarCollapsed,
				'left-0 opacity-100': !sidebarCollapsed,
			}"
		>
			<div class="flex items-center justify-between p-3 border-b border-stone-200">
				<h1>{{ config('app.name') }}</h1>
				<x-lux::logo class="w-6" />
			</div>

			<div class="flex-grow px-3 py-3 overflow-y-auto">
				<x-lux::sidebar />
			</div>

			<div class="p-3">
				<div x-data="{open: false}" x-on:click.outside="open = false" class="relative">
					<button x-on:click="open = !open" type="button" class="group flex items-center justify-between space-x-2 w-full px-3 py-1.5 bg-white shadow rounded cursor-pointer transition-colors duration-300 hover:bg-stone-50">
						<div class="flex items-center space-x-2">
							<div class="grid place-items-center w-7 h-7 rounded-full bg-stone-300i overflow-hidden">
								@if($avatarUrl = auth()->user()->avatarUrl)
									<img src="{{ $avatarUrl }}" class="w-full h-full object-cover" />
								@else
									<span>{{ str(auth()->user()->name)->substr(0, 1)->upper() }}</span>
								@endif
							</div>

							<span class="text-xs">{{ auth()->user()->name }}</span>
						</div>

						<div>
							<x-lux::tabler-icons.chevron-up x-bind:class="{'rotate-90': !open}" class="w-4 h-4 opacity-30 transition-all group-hover:opacity-60" />
						</div>
					</button>

					<div x-show="open" x-transition class="absolute bottom-0 left-0 w-full mb-12 px-1 py-1 bg-white rounded-md shadow">
						<ul class="space-y-1 text-sm">
							<x-lux::user-menu.item>
								<a href="{{ route('lux.profile') }}">
									<x-lux::user-menu.button icon="user-circle" label="Mi perfil" />
								</a>
							</x-lux::user-menu.item>

							<x-lux::user-menu.item>
								<form action="/logout" method="POST">
									@csrf
									<x-lux::user-menu.button icon="door-exit" label="Salir" />
								</form>
							</x-lux::user-menu.item>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<div
			x-data
			class="w-full min-h-screen bg-stone-50 transition-all duration-300"
			x-cloak
		>
			<div
					:class="{
                    'w-[calc(100%-16rem)] ml-64': !sidebarCollapsed,
                    'w-full ml-0': sidebarCollapsed,
                }"
					class="fixed flex items-center justify-between px-12 py-2 bg-white/80 backdrop-blur shadow transition-all duration-300 z-10"
			>
				<div class="flex items-center space-x-6">
					<button
						x-data
						type="button"
						x-on:click="toggleSidebar"
						class="relative flex items-center justify-center p-1.5 rounded transition-colors duration-300 bg-stone-200 text-stone-600 hover:bg-stone-800 hover:text-stone-100"
					>
						<x-lux::tabler-icons.layout-sidebar-left-collapse x-show="!sidebarCollapsed" class="w-6 h-6" />
						<x-lux::tabler-icons.layout-sidebar-left-expand x-show="sidebarCollapsed" class="w-6 h-6" />
					</button>
					<div>
						<h1 class="font-bold text-lg">{{ $title ?? 'Titulo' }}</h1>
						@isset($subtitle)
							<h2 class="text-xs text-stone-500">{{ $subtitle ?? 'Subtitulo' }}</h2>
						@endisset
					</div>
				</div>

				<div>
					{{ $actions ?? '' }}
				</div>
			</div>

			<div
				x-bind:class="{
					'px-12 ml-64': !sidebarCollapsed,
					'px-8 ml-0': sidebarCollapsed,
				}"
				class="mt-6 mb-12 pt-16 transition-all duration-300"
			>
				{{ $slot }}
			</div>
		</div>
	</div>

	@livewireScriptConfig
	@stack('js')
	<script defer src="https://unpkg.com/@alpinejs/ui@3.13.2-beta.1/dist/cdn.min.js"></script>
	@vite(['resources/js/lux.js', 'resources/js/draggable.js'])
</body>
</html>
