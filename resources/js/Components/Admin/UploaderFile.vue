<script setup>
import {computed, onMounted, reactive, ref} from "vue";
import {showToast} from "vant";
import 'vant/lib/index.css';

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    },
})

const emit = defineEmits(['update:modelValue'])

const upload_files = reactive({
    urls: props.modelValue,
})

const upload = reactive({
    beforeRead : (file) => {
        let type = file.name;
        type = type.substring(type.lastIndexOf('.') + 1);
        if(!/(doc|docx|xls|xlsx|txt)$/.test(type.toLowerCase())){
            showToast({
                message: 'Please upload word, excel, txt documents',
            });
            return false;
        }
        return true;
    },
    afterRead : (file) => {
        let name = file.file.name;
        let type = file.file.type;
        upload.uploadFile(type, file.content, name);
    },
    uploadFile : (type, base64, name) => {
        let data = {
            type: type,
            base64: base64,
            name: name
        };
        window.axios.post(route('admin.upload.file'), data).then(res => {
            if (res.status === 200) {
                if(res.data.success) {
                    upload_files.urls = res.data.data.url;
                    emit('update:modelValue', upload_files.urls);
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
    delete : (index) => {
        upload_files.urls = '';
        emit('update:modelValue', upload_files.urls);
    }
})


</script>

<template>
    <div class="relative w-full">
        <div class="absolute right-[3px] mt-[3px]">
            <van-uploader accept="*.*" :max-count="1" :before-read="upload.beforeRead" :after-read="upload.afterRead" :before-delete="upload.delete">
                <van-button icon="plus" type="success">上传文件</van-button>
            </van-uploader>
        </div>
        <input type="text"
               class="px-3 py-3 pr-[130px] max-w-full focus:ring focus:outline-none border rounded w-full"
               v-model="upload_files.urls" readonly />
    </div>
</template>
