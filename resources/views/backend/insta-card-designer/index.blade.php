@extends('backend.layouts.master')

@section('admin-content')
<style>
    #designerCanvas {
        width: 1080px;
        height: 1080px;
        background-color: white;
        position: relative;
        margin: 0 auto;
        overflow: hidden;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        border: 2px solid #ddd;
    }
    .draggable-element {
        position: absolute;
        cursor: move;
        user-select: none;
        border: 1px dashed #007bff;
        resize: both;
        overflow: auto;
    }
    .resize-handle {
        position: absolute;
        width: 10px;
        height: 10px;
        background-color: #007bff;
        bottom: 0;
        right: 0;
        cursor: se-resize;
    }
    .element-controls {
        position: absolute;
        top: -30px;
        left: 0;
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        padding: 5px;
        display: none;
        z-index: 1000;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h3>Insta Card Designer</h3>
                    
                    <div class="form-group">
                        <label>Add Element</label>
                        <div class="input-group">
                            <select id="elementType" class="form-control mr-2">
                                <option value="text">Text</option>
                                <option value="image">Image</option>
                                <option value="rectangle">Rectangle</option>
                            </select>
                            <input type="text" id="elementContent" class="form-control" placeholder="Enter text">
                            <div class="input-group-append">
                                <button id="addElement" class="btn btn-primary">Add</button>
                            </div>
                        </div>
                    </div>

                    <!-- Image Upload Section -->
                    <div id="imageUploadSection" style="display:none;" class="form-group">
                        <input type="file" id="imageUpload" class="form-control">
                        <div class="row mt-2">
                            <div class="col">
                                <input type="number" id="imageWidth" class="form-control" placeholder="Width">
                            </div>
                            <div class="col">
                                <input type="number" id="imageHeight" class="form-control" placeholder="Height">
                            </div>
                        </div>
                    </div>

                    <!-- Text Styling Section -->
                    <div id="textStylingSection">
                        <div class="form-group">
                            <label>Font Family</label>
                            <select id="fontFamilySelect" class="form-control">
                                <option value="Arial, sans-serif">Arial</option>
                                <option value="Montserrat, sans-serif">Montserrat</option>
                                <option value="Roboto, sans-serif">Roboto</option>
                                <option value="Open Sans, sans-serif">Open Sans</option>
                                <option value="Lato, sans-serif">Lato</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Text Styling</label>
                            <div class="row">
                                <div class="col">
                                    <input type="color" id="textColorPicker" class="form-control">
                                    <small>Text Color</small>
                                </div>
                                <div class="col">
                                    <select id="fontSizeSelect" class="form-control">
                                        <option value="16px">Small</option>
                                        <option value="24px" selected>Medium</option>
                                        <option value="36px">Large</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Rectangle Styling -->
                        <div id="rectangleStylingSection" style="display:none;">
                            <div class="form-group">
                                <label>Rectangle Styling</label>
                                <div class="row">
                                    <div class="col">
                                        <input type="color" id="rectangleColorPicker" class="form-control">
                                        <small>Fill Color</small>
                                    </div>
                                    <div class="col">
                                        <input type="number" id="rectangleWidth" class="form-control" placeholder="Width">
                                    </div>
                                    <div class="col">
                                        <input type="number" id="rectangleHeight" class="form-control" placeholder="Height">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button id="generateCard" class="btn btn-primary">Generate Card</button>
                        <button id="downloadCard" class="btn btn-success">Download Card</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div id="designerCanvas"></div>
        </div>
    </div>
</div>
@endsection

@push('footerscripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/interact.js/1.10.3/interact.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script>
$(document).ready(function() {
    const canvas = $('#designerCanvas');

    // Element Type Selection
    $('#elementType').on('change', function() {
        const type = $(this).val();
        
        // Reset and hide all sections
        $('#imageUploadSection, #rectangleStylingSection').hide();
        $('#elementContent').show();

        switch(type) {
            case 'image':
                $('#imageUploadSection').show();
                $('#elementContent').hide();
                break;
            case 'rectangle':
                $('#rectangleStylingSection').show();
                $('#elementContent').hide();
                break;
        }
    });

    // Create Draggable and Resizable Element
    function createDraggableElement(content, type = 'text') {
        const element = $('<div>', {
            class: 'draggable-element',
            css: {
                position: 'absolute',
                left: '50%',
                top: '50%',
                transform: 'translate(-50%, -50%)'
            }
        });

        // Add controls
        const controls = $('<div>', {
            class: 'element-controls',
            html: `
                <button class="btn btn-sm btn-danger remove-element">Remove</button>
                <button class="btn btn-sm btn-info bring-front">Front</button>
                <button class="btn btn-sm btn-info send-back">Back</button>
            `
        });

        switch(type) {
            case 'text':
                element.text(content);
                element.css({
                    padding: '10px',
                    backgroundColor: 'transparent',
                    color: $('#textColorPicker').val(),
                    fontSize: $('#fontSizeSelect').val(),
                    fontFamily: $('#fontFamilySelect').val()
                });
                break;

            case 'image':
                const img = $('<img>', {
                    src: content,
                    css: {
                        width: $('#imageWidth').val() ? `${$('#imageWidth').val()}px` : 'auto',
                        height: $('#imageHeight').val() ? `${$('#imageHeight').val()}px` : 'auto',
                        maxWidth: '100%',
                        maxHeight: '100%'
                    }
                });
                element.append(img);
                break;

            case 'rectangle':
                element.css({
                    width: `${$('#rectangleWidth').val() || 100}px`,
                    height: `${$('#rectangleHeight').val() || 100}px`,
                    backgroundColor: $('#rectangleColorPicker').val(),
                    border: '1px solid #000'
                });
                break;
        }

        // Add resize handle
        const resizeHandle = $('<div>', {
            class: 'resize-handle'
        });

        element.append(controls, resizeHandle);
        canvas.append(element);

        // Make element interactive
        makeElementInteractive(element);

        // Add event listeners for controls
        controls.find('.remove-element').on('click', function() {
            element.remove();
        });

        controls.find('.bring-front').on('click', function() {
            element.css('z-index', 100);
        });

        controls.find('.send-back').on('click', function() {
            element.css('z-index', 1);
        });

        // Hover effects for showing controls
        element.hover(
            function() {
                controls.show();
            },
            function() {
                controls.hide();
            }
        );

        return element;
    }

    // Make element draggable and resizable
    function makeElementInteractive(element) {
        interact(element[0])
            .draggable({
                inertia: true,
                modifiers: [
                    interact.modifiers.restrictRect({
                        restriction: 'parent',
                        endOnly: true
                    })
                ],
                autoScroll: true,
                listeners: {
                    move: dragMoveListener
                }
            })
            .resizable({
                edges: { bottom: true, right: true },
                listeners: {
                    move: function (event) {
                        const { x, y } = event.target.dataset;
                        Object.assign(event.target.style, {
                            width: `${event.rect.width}px`,
                            height: `${event.rect.height}px`
                        });
                    }
                }
            });
    }

    // Drag move listener
    function dragMoveListener(event) {
        const { target } = event;
        const x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx;
        const y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;

        Object.assign(target.style, {
            transform: `translate(${x}px, ${y}px)`
        });

        target.setAttribute('data-x', x);
        target.setAttribute('data-y', y);
    }

    // Add Element
    $('#addElement').on('click', function() {
        const type = $('#elementType').val();
        
        switch(type) {
            case 'text':
                const text = $('#elementContent').val();
                if (text) {
                    createDraggableElement(text, 'text');
                    $('#elementContent').val('');
                }
                break;

            case 'image':
                const fileInput = $('#imageUpload')[0];
                if (fileInput.files.length > 0) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        createDraggableElement(e.target.result, 'image');
                        fileInput.value = ''; // Clear file input
                    };
                    reader.readAsDataURL(fileInput.files[0]);
                }
                break;

            case 'rectangle':
                createDraggableElement('', 'rectangle');
                break;
        }
    });

    // Download Card
    $('#downloadCard').on('click', function() {
        // Hide controls before capturing
        $('.element-controls').hide();
        
        html2canvas(document.getElementById('designerCanvas'), {
            allowTaint: true,
            useCORS: true
        }).then(canvas => {
            const link = document.createElement('a');
            link.download = 'insta-card.png';
            link.href = canvas.toDataURL();
            link.click();
        });
    });
});
</script>
@endpush