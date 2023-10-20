<script setup>
import {computed, onMounted, reactive, ref} from "vue";
import {showToast} from "vant";

const props = defineProps({
    modelValue: {
        type: [Array, String]
    },
})

const emit = defineEmits(['update:modelValue'])

const init_files = () => {
    if (Array.isArray(images.urls)) {
        return images.urls.map(url => {
            return {
                url: url,
                status: 'finished'
            }
        })
    } else {
        if (images.urls !== null) {
            return [
                {
                    url: images.urls,
                    status: 'finished'
                }
            ]
        } else {
            return []
        }
    }
}

const images = reactive({
    urls: props.modelValue,
    files: [],
})

const upload = reactive({
    beforeRead: (file) => {
        if (!/\/(gif|jpg|jpeg|png|bmp)$/.test(file.type.toLowerCase())) {
            showToast({
                message: 'Please upload gif, jpg, png, jpeg, bmp documents',
            });
            return false;
        }
        return true;
    },
    afterRead: (file) => {
        let name = file.file.name;
        let type = file.file.type;
        type = type.substring(type.indexOf('/') + 1, type.length);

        if (file.file.size <= 500 * 1024) {
            upload.uploadImg(type, file.content, name);
        } else {
            let img = new Image();
            img.src = file.content;
            img.onload = function () {
                let baseImg = upload.compress(img);//压缩图片
                upload.uploadImg('jpeg', baseImg, name);
            };
        }
    },
    compress: (img) => {
        let canvas = document.createElement("canvas");
        let ctx = canvas.getContext('2d');
        let width = img.width;
        let height = img.height;
        canvas.width = width;
        canvas.height = height;
        ctx.drawImage(img, 0, 0, width, height);
        return canvas.toDataURL("image/jpeg", 0.3);
    },
    uploadImg: (type, base64, name) => {
        let data = {
            type: type,
            base64: base64,
            name: name
        };
        window.axios.post(route('admin.upload'), data).then(res => {
            if (res.status === 200) {
                if(res.data.success) {
                    if (Array.isArray(images.urls)) {
                        images.urls.push(res.data.data.url);
                    } else {
                        images.urls = res.data.data.url;
                        if (images.files.length > 1) {
                            images.files.splice(0, 1);
                        }
                    }
                    emit('update:modelValue', images.urls);
                } else {
                    showToast({
                        message: res.data.error.message,
                    });
                }
            } else {
                showToast({
                    message: "Upload failed",
                });
            }
        });
    },
    deleteImage: (index) => {
        if (Array.isArray(images.urls)) {
            images.urls.splice(index, 1);
            images.files.splice(index, 1);
        } else {
            images.urls = '';
            images.files = [];
        }
        emit('update:modelValue', images.urls);
    }
})

onMounted(() => {
    images.files = init_files();
})

</script>

<template>
    <van-uploader v-model="images.files"
                  :preview-options="{closeable: true}"
                  :before-read="upload.beforeRead"
                  :after-read="upload.afterRead"
                  :before-delete="upload.deleteImage"/>
</template>
