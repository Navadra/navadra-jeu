/*
    Copyright 2008-2016
        Matthias Ehmann,
        Michael Gerhaeuser,
        Carsten Miller,
        Bianca Valentin,
        Alfred Wassermann,
        Peter Wilfahrt

    This file is part of JSXGraph.

    JSXGraph is free software dual licensed under the GNU LGPL or MIT License.

    You can redistribute it and/or modify it under the terms of the

      * GNU Lesser General Public License as published by
        the Free Software Foundation, either version 3 of the License, or
        (at your option) any later version
      OR
      * MIT License: https://github.com/jsxgraph/jsxgraph/blob/master/LICENSE.MIT

    JSXGraph is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU Lesser General Public License for more details.

    You should have received a copy of the GNU Lesser General Public License and
    the MIT License along with JSXGraph. If not, see <http://www.gnu.org/licenses/>
    and <http://opensource.org/licenses/MIT/>.
 */


/*global JXG: true, define: true, window: true*/
/*jslint nomen: true, plusplus: true*/

/* depends:
 jxg
 utils/env
 utils/type
 */

/**
 * @fileoverview In this file the Text element is defined.
 */

define([
    'jxg', 'utils/env', 'utils/type'
], function (JXG, Env, Type) {

    "use strict";

    var priv = {
            CheckboxChangeEventHandler: function () {
                this._value = this.rendNodeCheckbox.checked;
                this.board.update();
            }
        };

    /**
     * @class This element is used to provide a constructor for special texts containing a form checkbox element.
     *
     * @pseudo
     * @description
     * @name Checkbox
     * @augments Text
     * @constructor
     * @type JXG.Text
     *
     * @param {number,function_number,function_String_String} x,y,label Parent elements for checkbox elements.
     *                     <p>
     *                     x and y are the coordinates of the lower left corner of the text box.
     *                      The position of the text is fixed,
     *                     x and y are numbers. The position is variable if x or y are functions.
     *                     <p>
     *                     The label of the input element may be given  as string.
     *
     * @example
     *   // Create a checkbox element at position [0,3].
     *   var checkbox = board.create('checkbox', [0, 3, 'Change Y'], {});
     *   var p = board.create('point', [
     *       function(){ return 0.5;}, // X-coordinate
     *       function() {
     *           y = 0.5;
     *           if (checkbox.Value()) {
     *               y += 0.5;
     *           }
     *           return y;
     *       }]);
     * </pre><div class="jxgbox"id="0e835e0b-ed0c-4b85-b682-78158c0e6f5c" style="width: 300px; height: 300px;"></div>
     * <script type="text/javascript">
     *   var t1_board = JXG.JSXGraph.initBoard('0e835e0b-ed0c-4b85-b682-78158c0e6f5c', {boundingbox: [-3, 6, 5, -3], axis: true, showcopyright: false, shownavigation: false});
     *   var checkbox = t1_board.create('checkbox', [0, 3, 'Change Y'], {});
     *   var p = t1_board.create('point', [
     *       function(){ return 0.5;}, // X-coordinate
     *       function() {
     *           y = 0.5;
     *           if (checkbox.Value()) {
     *               y += 0.5;
     *           }
     *           return y;
     *       }]);
     * </script><pre>
     *
     * The checkbox can be supplied with custom-made events by using the property rendNodeCheckbox.
     * @example
     * var checkbox = board.create('checkbox', [0, 4, 'Click me']),
     *     p = board.create('point', [1, 1]);
     *
     * JXG.addEvent(checkbox.rendNodeCheckbox, 'change', function() {
     *     if (this.Value()) {
     *         p.moveTo([4, 1]);
     *     } else {
     *         p.moveTo([1, 1]);
     *     }
     * }, checkbox);
     * </pre><div class="jxgbox"id="b2f2345a-057d-44ce-bd7a-6aaff70bc810" style="width: 300px; height: 300px;"></div>
     * <script type="text/javascript">
     * var t1_board = JXG.JSXGraph.initBoard('b2f2345a-057d-44ce-bd7a-6aaff70bc810', {boundingbox: [-3, 6, 5, -3], axis: true, showcopyright: false, shownavigation: false});
     * var checkbox = board.create('checkbox', [0, 4, 'Click me']),
     *     p = board.create('point', [1, 1]);
     *
     * JXG.addEvent(checkbox.rendNodeCheckbox, 'change', function() {
     *     if (this.Value()) {
     *         p.moveTo([4, 1]);
     *     } else {
     *         p.moveTo([1, 1]);
     *     }
     * }, checkbox);
     * </script><pre>
     */
    JXG.createCheckbox = function (board, parents, attributes) {
        var t, par,
            attr = Type.copyAttributes(attributes, board.options, 'checkbox');

        //if (parents.length !== 3) {
            //throw new Error("JSXGraph: Can't create checkbox with parent types '" +
            //    (typeof parents[0]) + "' and '" + (typeof parents[1]) + "'." +
            //    "\nPossible parents are: [[x,y], label]");
        //}

        par = [parents[0], parents[1],
            '<form style="display:inline">' +
            '<input type="checkbox" /><span></span>' +
            '</form>'
            ];

        t = JXG.createText(board, par, attr);
        t.type = Type.OBJECT_TYPE_CHECKBOX;

        t.rendNodeForm = t.rendNode.childNodes[0];
        t.rendNodeForm.id = t.rendNode.id + '_form';

        t.rendNodeCheckbox = t.rendNodeForm.childNodes[0];
        t.rendNodeCheckbox.id = t.rendNode.id + '_checkbox';

        t.rendNodeTag = t.rendNodeCheckbox; // Needed for unified treatment in setAttribute
        t.rendNodeTag.disabled = !!attr.disabled;

        t.rendNodeLabel = t.rendNodeForm.childNodes[1];
        t.rendNodeLabel.id = t.rendNode.id + '_label';
        t.rendNodeLabel.innerHTML = parents[2];

        // This sets the font-size of the checkbox itself
        t.visPropOld.fontsize = "0px";
        board.renderer.updateTextStyle(t, false);

        t._value = false;

        t.Value = function () {
            return this._value;
        };

        t.update = function () {
            if (this.needsUpdate) {
                this._value = this.rendNodeCheckbox.checked;
            }
            return this;
        };

        Env.addEvent(t.rendNodeCheckbox, 'change', priv.CheckboxChangeEventHandler, t);

        return t;
    };

    JXG.registerElement('checkbox', JXG.createCheckbox);

    return {
        createCheckbox: JXG.createCheckbox
    };
});
