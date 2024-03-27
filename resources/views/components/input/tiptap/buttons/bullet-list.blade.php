<button x-on:click="toggleBulletList" x-bind:class="{'bg-sky-100 text-sky-400': isActive('bulletList', updatedAt)}" class="p-0.5 rounded transition-colors hover:bg-sky-100">
    <x-lux::tabler-icons.list class="w-5 h-5" />
</button>