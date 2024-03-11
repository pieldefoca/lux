window.onload = function() {
    initDrag()

    window.addEventListener('refresh-drag', () => setTimeout(() => initDrag(), 1000))
}

let dragRoots;
let activeDragRoot;

function initDrag() {
    dragRoots = document.querySelectorAll('[drag-root]');

    if(!dragRoots) return

    dragRoots.forEach(dragRoot => {
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
    })

}

function dragstart(e) {
    activeDragRoot = elementDragRoot(e.target)

    e.target.closest('[drag-item]').setAttribute('dragging', true);
}

function drop(e) {
    let component = Livewire.find(e.target.closest('[wire\\:id]').getAttribute('wire:id'))

    let orderedIds = Array.from(activeDragRoot.querySelectorAll('[drag-item]')).map(itemEl => itemEl.getAttribute('drag-item'))

    let dragParams = activeDragRoot.getAttribute('drag-params') ?? []
    if(dragParams.length > 0) {
        dragParams = dragParams.split(' ')
    }

    let params = [...dragParams, orderedIds]

    component.call(activeDragRoot.getAttribute('drag-root'), ...params)

    window.dispatchEvent(new CustomEvent('refresh-drag'))
}

function dragenter(e) {
    let draggingEl = activeDragRoot.querySelector('[dragging]');
    let target = e.target.closest('[drag-item]');
    let childNodes = Array.from(activeDragRoot.childNodes);
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

function elementDragRoot(el) {
    return el.closest('[drag-root]')
}
