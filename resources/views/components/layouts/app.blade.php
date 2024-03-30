<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>{{ config('app.name') }} | Admin</title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700;900&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" />

	@vite(['resources/css/lux.css'])
</head>
<body
	x-data="{
		dark: localStorage.getItem('luxDarkMode', false),
	}"
	x-on:toggle-dark-mode.window="dark = !dark; localStorage.setItem('luxDarkMode', dark)"
	class="font-body text-text-color"
	x-bind:class="{'dark': dark}"
>
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
			class="fixed flex flex-col w-72 h-screen bg-background-light border-r border-line-color transition-all duration-500"
			x-cloak
			x-bind:class="{
				'-left-72 opacity-0': sidebarCollapsed,
				'left-0 opacity-100': !sidebarCollapsed,
			}"
		>
			<div class="flex items-center justify-between p-3 border-b border-line-color">
				<a href="/" target="_blank" class="flex items-center justify-between w-full">
					<h1>{{ config('app.name') }}</h1>
					<x-lux::logo class="h-9" />
				</a>
			</div>

			<div class="flex-grow px-3 py-3 overflow-y-auto">
				<x-lux.sidebar />
			</div>

			<div class="p-3">
{{--				<div>--}}
{{--					<button x-on:click="$dispatch('toggle-dark-mode')">--}}
{{--						<x-lux::tabler-icons.sun x-show="dark" />--}}
{{--						<x-lux::tabler-icons.moon-stars x-show="!dark" />--}}
{{--					</button>--}}
{{--				</div>--}}
				<div x-data="{open: false}" x-on:click.outside="open = false" class="relative">
					<button x-on:click="open = !open" type="button" class="group flex items-center justify-between space-x-2 w-full px-3 py-1.5 bg-card-bg-color dark:border dark:border-line-color shadow rounded cursor-pointer transition-colors duration-300 hover:bg-stone-50">
						<div class="flex items-center space-x-2">
							<div class="grid place-items-center w-7 h-7 rounded-full bg-stone-300 overflow-hidden">
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
									<x-lux::user-menu.button type="submit" icon="door-exit" label="Salir" />
								</form>
							</x-lux::user-menu.item>
						</ul>
					</div>
				</div>
			</div>
		</div>

		<div x-data class="w-full min-h-screen bg-background transition-all duration-300" x-cloak>
			<div
				x-bind:class="{
					'px-12 ml-72': !sidebarCollapsed,
					'px-8 ml-0': sidebarCollapsed,
				}"
				class="min-h-screen transition-all duration-300"
			>
				{{ $slot }}
			</div>
		</div>
	</div>

	<livewire:media-manager.selector />
    <livewire:media-manager.slideover :deletable="Route::is('lux.media.index')" />

	@stack('js')
	<script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>
	<script defer src="https://unpkg.com/@alpinejs/ui@3.13.2-beta.1/dist/cdn.min.js"></script>
	@vite(['resources/js/lux.js'])
</body>
</html>
