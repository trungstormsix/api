
(function (factory) {
    if (typeof define === 'function' && define.amd) {
// AMD. Register as anonymous module.
        define(['jquery'], factory);
    } else if (typeof exports === 'object') {
// Node / CommonJS
        factory(require('jquery'));
    } else {
// Browser globals.
        factory(jQuery);
    }
})(function ($) {

    'use strict';
    var console = window.console || {log: function () {}};
    function CropAvatar($element) {
        this.$container = $element;
        this.$avatarView = this.$container.find('.avatar-view');
        this.$avatar = this.$avatarView.find('#avt_image');
        this.$avatarModal = $('#profilePictureModal');
        this.$loading = $('#avt_loading');
        this.$avatarForm = this.$avatarModal.find('.avatar-form');
        this.$avatarUpload = this.$avatarForm.find('.avatar-upload');
        this.$avatarSrc = this.$avatarForm.find('.avatar-src');
        this.$avatarData = this.$avatarForm.find('.avatar-data');
        this.$avatarInput = this.$avatarForm.find('.avatar-input');
        this.$uploadButton = this.$avatarForm.find('#upload_avatar_btn');
        this.$avatarSave = this.$avatarForm.find('.avatar-save');
        this.$avatarBtns = this.$avatarForm.find('.avatar-btns');
        this.$avatarWrapper = this.$avatarModal.find('.avatar-wrapper');
        this.$slider = document.getElementById('range_slider');
        this.init();
    }

    CropAvatar.prototype = {
        constructor: CropAvatar,
        support: {
            fileList: !!$('<input type="file">').prop('files'),
            blobURLs: !!window.URL && URL.createObjectURL,
            formData: !!window.FormData
        },
        init: function () {
            var _this = this;
            this.support.datauri = this.support.fileList && this.support.blobURLs;
            if (!this.support.formData) {
                this.initIframe();
            }
            noUiSlider.create(this.$slider, {
                start: 0,
                connect: [true, false], range: {
                    'min': 0,
                    'max': 2
                }
            });
            this.$slider.noUiSlider.on('slide', function (values, handle) {

                _this.$img.cropper('zoomTo', values[0]);
            });
            this.initTooltip();
            this.initModal();
            this.addListener();


        },

        addListener: function () {
            this.$avatarView.on('click', $.proxy(this.click, this));
            this.$avatarInput.on('change', $.proxy(this.change, this));
            this.$avatarForm.on('submit', $.proxy(this.submit, this));
            this.$avatarBtns.on('click', $.proxy(this.rotate, this));
            this.$uploadButton.on('click', $.proxy(this.openFile, this));
        },
        openFile: function () {
            this.$avatarInput.trigger("click")
        },
        initTooltip: function () {
            this.$avatarView.tooltip({
                placement: 'bottom'
            });
        },
        initModal: function () {
            this.$avatarModal.modal({
                show: false
            });
        },
        initPreview: function () {
            var url = this.$avatar.attr('src');
        },
        initIframe: function () {
            var target = 'upload-iframe-' + (new Date()).getTime();
            var $iframe = $('<iframe>').attr({
                name: target,
                src: ''
            });
            var _this = this;
            // Ready ifrmae
            $iframe.one('load', function () {

                // respond response
                $iframe.on('load', function () {
                    var data;
                    try {
                        data = $(this).contents().find('body').text();
                    } catch (e) {
                        console.log(e.message);
                    }

                    if (data) {
                        try {
                            data = $.parseJSON(data);
                        } catch (e) {
                            console.log(e.message);
                        }

                        _this.submitDone(data);
                    } else {
                        _this.submitFail('Image upload failed!');
                    }

                    _this.submitEnd();
                });
            });
            this.$iframe = $iframe;
            this.$avatarForm.attr('target', target).after($iframe.hide());
        },
        click: function () {
            this.$avatarModal.modal('show');
            this.startCropper();
            this.initPreview();
        },
        change: function () {
            var files;
            var file;
            if (this.support.datauri) {
                files = this.$avatarInput.prop('files');
                if (files.length > 0) {
                    file = files[0];
                    if (this.isImageFile(file)) {
                        if (this.url) {
                            URL.revokeObjectURL(this.url); // Revoke the old one
                        }

                        this.url = URL.createObjectURL(file);
                        this.startCropper();
                    }
                }
            } else {
                file = this.$avatarInput.val();
                if (this.isImageFile(file)) {
                    this.syncUpload();
                }
            }
        },
        submit: function () {
            if (!this.$avatarSrc.val() && !this.$avatarInput.val()) {
                return false;
            }

            if (this.support.formData) {
                this.ajaxUpload();
                return false;
            }
        },
        rotate: function (e) {
            var data;
            if (this.active) {
                data = $(e.target).data();
                if (data.method) {
                    this.$img.cropper(data.method, data.option);
                }
            }
        },
        isImageFile: function (file) {
            if (file.type) {
                return /^image\/\w+$/.test(file.type);
            } else {
                return /\.(jpg|jpeg|png|gif)$/.test(file);
            }
        },
        startCropper: function () {
            var _this = this;
            if (!this.url) {
                this.url = $(".avatar-view img").attr("src");
            }
            if (this.active) {
                this.$img.cropper('replace', this.url);
            } else {
                this.$img = this.$avatarWrapper.find('img');
//                this.$avatarWrapper.empty().html(this.$img);
                this.$img.cropper({
                    aspectRatio: 16 / 9,
                    zoomable: true,
                    scalable: true,
                    movable: true,
                    background: true,
                    dragMode: 'move',
                    guides: true,
                    cropBoxResizable: true,
                    cropBoxMovable: true,
                    viewMode: 2,
                    autoCropArea: 1,
                    highlight: true,
                    center: true,
                    zoomOnWheel: true,
                    built: function () {
                        $(this).cropper('getCroppedCanvas').toBlob(function (blob) {
                            console.log("ENtereedddd");
                            var formData = new FormData();
                            formData.append('croppedImage', blob);
                            console.log(formData);
                        });
                    },
                    crop: function (e) {
                        var json = [
                            '{"x":' + e.x,
                            '"y":' + e.y,
                            '"height":' + e.height,
                            '"width":' + e.width,
                            '"rotate":' + e.rotate + '}'
                        ].join();
                        _this.$avatarSrc.val(_this.url);
                        _this.$avatarData.val(json);
                        
                    }
                });

                this.active = true;
            }
            this.$slider.noUiSlider.set(1);
            this.$avatarModal.one('hidden.bs.modal', function () {

            });
        },

        ajaxUpload: function () {
            var url = this.$avatarForm.attr('action');
            var data = new FormData(this.$avatarForm[0]);
            var _this = this;
            $.ajax(url, {
                type: 'post',
                data: data,
                dataType: 'json',
                processData: false,
                contentType: false,
                beforeSend: function () {
                    _this.submitStart();
                },
                success: function (data) {
                    _this.submitDone(data);
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    _this.submitFail(textStatus || errorThrown);
                },
                complete: function () {
                    _this.submitEnd();
                     location.reload(); 
                }
            });
        },
        syncUpload: function () {
            this.$avatarSave.click();
        },
        submitStart: function () {
            this.$loading.fadeIn();
        },
        submitDone: function (data) {
            console.log(data);
            if ($.isPlainObject(data) && data.state === 200) {
                if (data.result) {
                    this.url = data.result;
                    if (this.support.datauri || this.uploaded) {
                        this.uploaded = false;
                        this.cropDone();
                    } else {
                        this.uploaded = true;
                        this.$avatarSrc.val(this.url);
                        this.startCropper();
                    }

                    this.$avatarInput.val('');
                } else if (data.message) {
                    this.alert(data.message);
                }
            } else {
                this.alert('Failed to response');
            }
        },
        submitFail: function (msg) {
            this.alert(msg);
        },
        submitEnd: function () {
            this.$loading.fadeOut();
        },
        cropDone: function () {
            this.$avatarForm.get(0).reset();
            this.$avatar.attr('src', this.url);
            if (this.url) {
                $("#user_avatar").attr("src", this.url);         
                jQuery('#thumb').val(this.url);
            }
            this.$avatarModal.modal('hide');
        },
        alert: function (msg) {
            alert(msg)
            var $alert = [
                '<div class="alert alert-danger avatar-alert alert-dismissable">',
                '<button type="button" class="close" data-dismiss="alert">&times;</button>',
                    msg,
                '</div>'
            ].join('');
            this.$avatarUpload.after($alert);
        }
    };
    $(function () {
        return new CropAvatar($('.picvoc_cat'));
    });
});
