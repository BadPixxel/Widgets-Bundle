
import { Controller } from '@hotwired/stimulus';
import { getComponent } from '@symfony/ux-live-component';

export default class ModalController extends Controller {

    modal = null;

    async initialize() {
        //==============================================================================
        // Initialize Component
        this.component = await getComponent(this.element);
        //==============================================================================
        // Initialize Modal
        this.modal = $(this.element).children(".modal").modal();
        //==============================================================================
        // Component Request to Close Modal
        window.addEventListener('modal:close', () => this.modal.modal('hide'));
        //==============================================================================
        // Modal Closed by User
        $(this.element).on('hide.bs.modal', (e) => this.component.emit("modal:close", {}));
    }
}