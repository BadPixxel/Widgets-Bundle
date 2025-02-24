
import { Controller } from '@hotwired/stimulus';
import { getComponent } from '@symfony/ux-live-component';

export default class ModalController extends Controller {

    modal = null;

    async initialize() {
        //==============================================================================
        // Initialize Component
        this.component = await getComponent(this.element);

        this.component.on('render:finished', (component) => {

            let sortElement = $(component.element).children(".collection-widgets");
            //==============================================================================
            // Check if Sortable is Enabled
            if (!component.getData("configuration").sortable) {
                if (sortElement.hasClass("ui-sortable")) {
                    sortElement.sortable("destroy")
                }

                return;
            }
            //==============================================================================
            // Init Sortable
            sortElement.sortable({
                update: (event) => component.emit("sort", {
                    ordering: $(event.target).sortable('toArray', { attribute: 'data-id' })
                }),
            });
        });
    }
}