<div x-id="['toggle']" class="toggle-switch relative w-[60px] h-[34px]">
    <input {{ $attributes }} class="peer" :id="$id('toggle')" type="checkbox" style="display: none;">
    <label class="absolute top-0 left-0 w-[55px] h-[34px] rounded-full bg-gray-200 cursor-pointer transition-all duration-300 before:absolute before:top-[3px] before:left-[3px] before:w-[28px] before:h-[28px] before:rounded-full before:bg-white before:transition-all before:duration-300 before:shadow peer-checked:bg-green-300 peer-checked:before:translate-x-[21px]" :for="$id('toggle')"></label>
</div>