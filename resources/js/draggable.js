window.onload = function() {
    initDrag()

    window.addEventListener('refresh-drag', () => setTimeout(() => initDrag(), 1000))
}

var dragRoot;

function initDrag() {
    dragRoot = document.querySelector('[drag-root]');

    if(!dragRoot) return

    dragRoot.querySelectorAll('[drag-item]').forEach(el => {
        el.removeEventListener('dragstart', dragstart)
        el.addEventListener('dragstart', dragstart)

        el.removeEventListener('drop', drop)
        el.addEventListener('drop', drop)

        el.removeEventListener('dragenter', dragenter)
        el.addEventListener('dragenter', dragenter)

        el.removeEventListener('dragleave', dragleave)
        el.addEventListener('dragleave', dragleave)

        el.removeEventListener('dragover', dragover)
        el.addEventListener('dragover', dragover);

        el.removeEventListener('dragend', dragend)
        el.addEventListener('dragend', dragend);
    });
}

function dragstart(e) {
    e.target.closest('[drag-item]').setAttribute('dragging', true);
}

function drop(e) {
    let component = Livewire.find(e.target.closest('[wire\\:id]').getAttribute('wire:id'))

    let orderedIds = Array.from(dragRoot.querySelectorAll('[drag-item]')).map(itemEl => itemEl.getAttribute('drag-item'))

    component.call(dragRoot.getAttribute('drag-root'), orderedIds)

    window.dispatchEvent(new CustomEvent('refresh-drag'))
}

function dragenter(e) {
    let draggingEl = dragRoot.querySelector('[dragging]');
    let target = e.target.closest('[drag-item]');
    let childNodes = Array.from(dragRoot.childNodes);
    let draggingElementIsBeforeTarget = childNodes.indexOf(draggingEl) < childNodes.indexOf(target);

    if(draggingEl != target) {
        if(draggingElementIsBeforeTarget) {
            target.after(draggingEl);
        } else {
            target.before(draggingEl);
        }
    }

    e.preventDefault();
}

function dragleave(e) {
    //
}

function dragover(e) {
    e.preventDefault();
}

function dragend(e) {
    e.target.closest('[drag-item]').setAttribute('dragging', true);
}
