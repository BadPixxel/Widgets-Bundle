/*
 *  Copyright (C) BadPixxel <www.badpixxel.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

//------------------------------------------------------------------------------
// Register Widgets Stimulus Controller
//------------------------------------------------------------------------------
import ModalController from './controllers/widgets_modal_controller';
app.register('BadPixxelWidgetsModalController', ModalController);

import SortableController from './controllers/widgets_sortable_controller';
app.register('BadPixxelWidgetsSortableController', SortableController);
