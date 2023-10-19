<script setup>
import { useForm} from "@inertiajs/vue3"
import {reactive, ref} from "vue";

const images = ref(null);

const upload = reactive({
    beforeRead : (file) => {
        if (!/\/(gif|jpg|jpeg|png|bmp)$/.test(file.type.toLowerCase())) {
            return false;
        }
        return true;
    },
    afterRead : (file, name) => {
        let type = file.file.type;
        type = type.substring(type.indexOf('/') + 1, type.length);

        if (file.file.size <= 500 * 1024) {
            uploadImg(type, file.content, name);
        } else {
            let img = new Image();
            img.src = file.content;
            img.onload = function () {
                let baseImg = compress(img);//压缩图片
                uploadImg('jpeg', baseImg, name);
            };
        }
    },
    compress : (img) => {
        let canvas = document.createElement("canvas");
        let ctx = canvas.getContext('2d');
        let width = img.width;
        let height = img.height;
        canvas.width = width;
        canvas.height = height;
        ctx.drawImage(img, 0, 0, width, height);
        return canvas.toDataURL("image/jpeg", 0.3);
    },
    uploadImg : (type, base64, name) => {
        axios.post('/api/v1/upload', {type, base64}).then((res) => {
            if (res.data.success) {
                showToast('success')
                data.image.push(res.data.data.url);
            }
        }).catch(error => {
            showToast(error.response.data.message);
        })
    },
    deleteImage : (index) => {
        images.splice(index, 1);
    }
})

const form = useForm({

})
</script>

<template>
    <van-uploader :after-read="upload.afterRead" />
</template>
