<?php
use System\Libraries\Session;
?>
<script src="https://cdn.tailwindcss.com"></script>

<div id="app" class="container mx-auto p-4">
        <h1 class="text-xl font-bold mb-4">Edit <?= $postType['name'] ?></h1>
        <!-- message -->
         <div id="message"></div>         
        <!-- Form thêm Posttype sẽ được đặt ở đây -->
        <posttype-form></posttype-form>
    </div> 
    <!-- Import Vue.js -->
    <script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
    <!-- Script cho Vue component -->
    <script>
        var posttypeUrl = "<?= admin_url('posttype') ?>";
        var posttypeEditUrl = "<?= admin_url('posttype/edit/'.$postType['id']) ?>";
        var languages =   JSON.parse( '<?= json_encode($languages) ?>');
        var jsonString = <?= json_encode($postType['languages']) ?>;
        var langSelected = JSON.parse(jsonString);
        var fieldString = <?= json_encode($postType['fields']) ?>;
        var fields = JSON.parse(fieldString);
    Vue.component('field-item', {
        props: ['field', 'availableFieldTypes', 'postTypesList', 'postStatusOptions', 'parentField', 'index', 'fieldsArray'],
        data() {
            return {
                isCollapsed: this.field.collapsed || false,
            };
        },
        methods: {
            addOption() {
                this.field.options.push({ value: '', label: '', is_group: false });
            },
            removeOption(optionIndex) {
                this.field.options.splice(optionIndex, 1);
            },
            addRepeaterField() {
                this.field.fields.push({
                    type: '',
                    label: '',
                    field_name: '',
                    description: '',
                    required: false,
                    visibility: true,
                    css_class: '',
                    placeholder: '',
                    default_value: '',
                    order: this.field.fields.length + 1,
                    min: null,
                    max: null,
                    options: [],
                    rows: null,
                    allow_types: [],
                    max_file_size: null,
                    multiple: false,
                    post_type_reference: null,
                    post_status_filter: '',
                    fields: [],
                    collapsed: false,
                });
            },
            removeRepeaterField(index) {
                this.field.fields.splice(index, 1);
            },
            toggleCollapse() {
                this.isCollapsed = !this.isCollapsed;
                this.field.collapsed = this.isCollapsed;
            },
            moveUp() {
                if (this.index > 0) {
                    const temp = this.fieldsArray[this.index - 1];
                    Vue.set(this.fieldsArray, this.index - 1, this.field);
                    Vue.set(this.fieldsArray, this.index, temp);
                }
            },
            moveDown() {
                if (this.index < this.fieldsArray.length - 1) {
                    const temp = this.fieldsArray[this.index + 1];
                    Vue.set(this.fieldsArray, this.index + 1, this.field);
                    Vue.set(this.fieldsArray, this.index, temp);
                }
            },
        },
        template: `
        <div class="mb-2 p-2 border rounded">
            <div class="flex justify-between items-center mb-1">
                <div class="flex items-center">
                    <button @click="toggleCollapse" class="mr-2 text-sm focus:outline-none">
                        <span v-if="isCollapsed">[+]</span>
                        <span v-else>[-]</span>
                    </button>
                    <h3 class="font-semibold text-sm">Field: {{ field.label || 'Chưa đặt tên' }}</h3>
                </div>
                <div class="flex items-center space-x-2">
                    <button @click="moveUp" class="text-gray-500 hover:text-gray-700 text-sm focus:outline-none" :disabled="index === 0">
                        ↑
                    </button>
                    <button @click="moveDown" class="text-gray-500 hover:text-gray-700 text-sm focus:outline-none" :disabled="index === fieldsArray.length - 1">
                        ↓
                    </button>
                    <button @click="$emit('remove')" class="text-red-500 text-sm focus:outline-none">
                        🗑️
                    </button>
                </div>
            </div>
            <div v-if="!isCollapsed">
                <!-- Nội dung chi tiết của field -->
                <!-- Loại Field -->
                <div class="mb-1">
                    <label class="block text-gray-700 text-sm">Loại Field</label>
                    <select v-model="field.type" class="mt-0.5 pl-1 block w-full border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option disabled value="">Chọn loại field</option>
                        <option v-for="type in availableFieldTypes" :value="type">{{ type }}</option>
                    </select>
                </div>

                <!-- Các thuộc tính chung -->
                <div class="mb-1">
                    <label class="block text-gray-700 text-sm">Label</label>
                    <input v-model="field.label" type="text" class="mt-0.5 pl-1 block w-full border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-1">
                    <label class="block text-gray-700 text-sm">Field Name (Slug)</label>
                    <input v-model="field.field_name" type="text" class="mt-0.5 pl-1 block w-full border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-1">
                    <label class="block text-gray-700 text-sm">Description</label>
                    <input v-model="field.description" type="text" class="mt-0.5 pl-1 block w-full border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="flex items-center mb-1 space-x-4">
                    <div class="flex items-center">
                        <input v-model="field.required" type="checkbox" class="mr-1">
                        <label class="text-gray-700 text-sm">Required</label>
                    </div>
                    <div class="flex items-center">
                        <input v-model="field.visibility" type="checkbox" class="mr-1">
                        <label class="text-gray-700 text-sm">Visibility</label>
                    </div>
                </div>
                <div class="mb-1">
                    <label class="block text-gray-700 text-sm">CSS Class Name</label>
                    <input v-model="field.css_class" type="text" class="mt-0.5 pl-1 block w-full border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-1">
                    <label class="block text-gray-700 text-sm">Placeholder</label>
                    <input v-model="field.placeholder" type="text" class="mt-0.5 pl-1 block w-full border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-1">
                    <label class="block text-gray-700 text-sm">Default Value</label>
                    <input v-model="field.default_value" type="text" class="mt-0.5 pl-1 block w-full border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <!-- Thuộc tính Min và Max -->
                <div v-if="['Text', 'Email', 'Number', 'Password', 'Date', 'DateTime', 'URL'].includes(field.type)" class="flex space-x-2 mb-1">
                    <div class="w-1/2">
                        <label class="block text-gray-700 text-sm">Min</label>
                        <input v-model.number="field.min" type="number" class="mt-0.5 pl-1 block w-full border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="w-1/2">
                        <label class="block text-gray-700 text-sm">Max</label>
                        <input v-model.number="field.max" type="number" class="mt-0.5 pl-1 block w-full border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <!-- Thuộc tính riêng cho Textarea -->
                <div v-if="field.type === 'Textarea'" class="mb-1">
                    <label class="block text-gray-700 text-sm">Rows</label>
                    <input v-model.number="field.rows" type="number" class="mt-0.5 pl-1 block w-full border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Thuộc tính cho Checkbox, Radio -->
                <div v-if="['Checkbox', 'Radio'].includes(field.type)" class="mb-1">
                    <label class="block text-gray-700 text-sm">Options</label>
                    <div class="flex flex-wrap -mx-1">
                        <div v-for="(option, optionIndex) in field.options" :key="optionIndex" class="w-1/2 px-1 mb-1 flex items-center">
                            <input v-model="option.value" placeholder="Value" class="w-1/2 pl-1 border border-gray-300 rounded-md text-sm mr-1 focus:ring-blue-500 focus:border-blue-500">
                            <input v-model="option.label" placeholder="Label" class="w-1/2 pl-1 border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                            <button @click="removeOption(optionIndex)" class="text-red-500 text-sm ml-1">Xóa</button>
                        </div>
                    </div>
                    <button @click="addOption" class="mt-1 px-2 py-1 bg-blue-500 text-white text-sm rounded">Thêm Option</button>
                </div>

                <!-- Thuộc tính cho Select -->
                <div v-if="field.type === 'Select'" class="mb-1">
                    <label class="block text-gray-700 text-sm">Options</label>
                    <div class="flex flex-wrap -mx-1">
                        <div v-for="(option, optionIndex) in field.options" :key="optionIndex" class="w-full px-1 mb-1 flex items-center">
                            <input v-model="option.is_group" type="checkbox" class="mr-1">
                            <label class="text-sm mr-1">Group</label>
                            <input v-model="option.value" placeholder="Value" class="w-1/3 pl-1 border border-gray-300 rounded-md text-sm mr-1 focus:ring-blue-500 focus:border-blue-500">
                            <input v-model="option.label" placeholder="Label" class="w-1/3 pl-1 border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                            <button @click="removeOption(optionIndex)" class="text-red-500 text-sm ml-1">Xóa</button>
                        </div>
                    </div>
                    <button @click="addOption" class="mt-1 px-2 py-1 bg-blue-500 text-white text-sm rounded">Thêm Option</button>
                    <div class="flex items-center mt-1">
                        <input v-model="field.multiple" type="checkbox" class="mr-1">
                        <label class="text-sm">Multiple</label>
                    </div>
                </div>

                <!-- Thuộc tính cho File, Image, Images Gallery -->
                <div v-if="['File', 'Image', 'Images Gallery'].includes(field.type)" class="mb-1">
                    <label class="block text-gray-700 text-sm">Allow Types</label>
                    <div class="flex flex-wrap -mx-1 mb-1">
                        <div v-for="type in ['jpg', 'gif', 'png', 'jpeg', 'webp', 'mp4', 'mp3', 'pdf', 'zip', 'rar', 'exe']" :key="type" class="w-1/4 px-1 mb-1 flex items-center">
                            <input v-model="field.allow_types" :value="type" type="checkbox" class="mr-1">
                            <label class="text-sm">{{ type }}</label>
                        </div>
                    </div>
                    <div class="mb-1">
                        <label class="block text-gray-700 text-sm">Max File Size (MB)</label>
                        <input v-model.number="field.max_file_size" type="number" class="mt-0.5 pl-1 block w-full border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex items-center mb-1">
                        <input v-model="field.multiple" type="checkbox" class="mr-1">
                        <label class="text-sm">Multiple</label>
                    </div>
                </div>

                <!-- Thuộc tính cho WYSIWYG Editor -->
                <div v-if="field.type === 'WYSIWYG Editor'" class="mb-1">
                    <p class="text-gray-600 text-sm">Field này sẽ sử dụng class <code>fast-editors</code> để gọi trình soạn thảo.</p>
                </div>

                <!-- Thuộc tính cho Reference -->
                <div v-if="field.type === 'Reference'" class="mb-1">
                    <label class="block text-gray-700 text-sm">Chọn Post Type liên kết</label>
                    <select v-model="field.post_type_reference" class="mt-0.5 pl-1 block w-full border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option disabled value="">Chọn Post Type</option>
                        <option v-for="pt in postTypesList" :value="pt.id">{{ pt.name }}</option>
                    </select>
                    <label class="block text-gray-700 text-sm mt-1">Trạng thái bài viết</label>
                    <select v-model="field.post_status_filter" class="mt-0.5 pl-1 block w-full border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option v-for="status in postStatusOptions" :value="status.value">{{ status.label }}</option>
                    </select>
                </div>

                <!-- Thuộc tính cho Repeater -->
                <div v-if="field.type === 'Repeater'" class="mb-1">
                    <h4 class="font-semibold text-sm mb-1">Fields trong Repeater</h4>
                    <div v-for="(repField, repIndex) in field.fields" :key="repIndex">
                        <field-item
                            :field="repField"
                            :available-field-types="availableFieldTypes.filter(t => t !== 'Repeater')"
                            :post-types-list="postTypesList"
                            :post-status-options="postStatusOptions"
                            @remove="removeRepeaterField(repIndex)"
                            :parent-field="field"
                            :index="repIndex"
                            :fields-array="field.fields"
                        ></field-item>
                    </div>
                    <button @click="addRepeaterField" class="mt-1 px-2 py-1 bg-blue-500 text-white text-sm rounded">Thêm Field vào Repeater</button>
                </div>
            </div>
        </div>
        `
    });

    // Component chính
    Vue.component('posttype-form', {
        data() {
            return {
                posttype: {
                    name: '<?= $postType['name'] ?>',
                    slug: '<?= $postType['slug'] ?>',
                    status: '<?= $postType['status'] ?>',
                    languages: langSelected,
                    fields: fields,
                },
                languages: languages,
                availableFieldTypes: [
                    'Text', 'Email', 'Number', 'Password', 'Date', 'DateTime', 'URL',
                    'Textarea', 'Checkbox', 'Radio', 'Select', 'File', 'Image', 'Images Gallery',
                    'WYSIWYG Editor', 'Reference', 'Repeater'
                ],
                postTypesList: [
                    { id: 1, name: 'Bài viết' },
                    { id: 2, name: 'Sản phẩm' },
                    // Thêm các Post Type khác nếu cần
                ],
                postStatusOptions: [
                    { value: 'active', label: 'Active' },
                    { value: 'inactive', label: 'Inactive' },
                ],
            }
        },
        methods: {
            addField() {
                // Thu gọn field gần nhất (field cuối cùng)
                if (this.posttype.fields.length > 0) {
                    this.posttype.fields[this.posttype.fields.length - 1].collapsed = true;
                }
                // Thêm field mới
                this.posttype.fields.push({
                    type: '',
                    label: '',
                    field_name: '',
                    description: '',
                    required: false,
                    visibility: true,
                    css_class: '',
                    placeholder: '',
                    default_value: '',
                    order: this.posttype.fields.length + 1,
                    min: null,
                    max: null,
                    options: [],
                    rows: null,
                    allow_types: [],
                    max_file_size: null,
                    multiple: false,
                    post_type_reference: null,
                    post_status_filter: '',
                    fields: [],
                    collapsed: false,
                });
            },
            removeField(index) {
                this.posttype.fields.splice(index, 1);
            },
            submitForm() {
                // Xử lý gửi dữ liệu đến server
                fetch(posttypeEditUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(this.posttype)
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                       // direct to list posttype url = url_admin('posttype')
                          window.location.href = posttypeUrl;
                    } else {
                       // add to div message data.message
                       const messageDiv = document.getElementById('message');
                        if (messageDiv) {
                            messageDiv.innerHTML = data.message;
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error.message);
                });
            }
        },
        template: `
            <div>
                <!-- Form nhập Tên Posttype và Slug -->
                <div class="mb-3">
                    <label class="block text-gray-700 text-sm">Tên Posttype</label>
                    <input v-model="posttype.name" type="text" class="mt-0.5 pl-1 block w-full border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-3">
                    <label class="block text-gray-700 text-sm">Slug</label>
                    <input v-model="posttype.slug" type="text" class="mt-0.5 pl-1 block w-full border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <!-- Status -->

                <div class="mb-3">
                    <h2 class="text-lg font-semibold mb-1">Status</h2>
                    <select v-model="posttype.status" class="mt-0.5 pl-1 block w-full border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                        <option v-for="option in postStatusOptions" :key="option.value" :value="option.value">
                            {{ option.label }}
                        </option>
                    </select>
                </div>

                <!-- Danh sách Languages -->
                <div class="mb-3">
                    <h2 class="text-lg font-semibold mb-1">Languages</h2>
                    <div v-for="language in languages" :key="language.id" class="mb-1">
                        <label>
                            <input type="checkbox" :value="language.code" v-model="posttype.languages">
                            {{ language.name }}
                        </label>
                    </div>
                </div>

                <!-- Danh sách Fields -->
                <div class="mb-3">
                    <h2 class="text-lg font-semibold mb-1">Fields</h2>
                    <div v-for="(field, index) in posttype.fields" :key="index">
                        <field-item
                            :field="field"
                            :available-field-types="availableFieldTypes"
                            :post-types-list="postTypesList"
                            :post-status-options="postStatusOptions"
                            @remove="removeField(index)"
                            :parent-field="null"
                            :index="index"
                            :fields-array="posttype.fields"
                        ></field-item>
                    </div>
                    <button @click="addField" class="px-3 py-1 bg-blue-500 text-white text-sm rounded">Thêm Field</button>
                </div>

                <!-- Hiển thị JSON của form để kiểm tra -->
                <div class="mb-3">
                    <h2 class="text-lg font-semibold mb-1">JSON Form Data</h2>
                    <pre class="text-xs">{{ JSON.stringify(posttype, null, 2) }}</pre>
                </div>

                <!-- Nút submit -->
                <button @click="submitForm" class="px-3 py-1 bg-green-500 text-white text-sm rounded">Lưu Posttype</button>
            </div>
        `
    });
    
    new Vue({
        el: '#app',
    });
    </script>