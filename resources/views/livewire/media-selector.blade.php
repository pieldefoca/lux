<div style="display: none;">
    <input
        @lux-media.window="
            $wire.key = $event.detail.key
            $el.click()
        "
        wire:model="file"
        type="file"
    />
</div>