<button x-on:click="toggleUnderline" x-bind:class="{'bg-sky-100 text-sky-400': isActive('underline', updatedAt)}" class="p-0.5 rounded transition-colors hover:bg-sky-100">
    <x-lux::tabler-icons.underline class="w-5 h-5" />
</button>