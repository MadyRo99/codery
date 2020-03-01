<template>
    <div class="container create-article">
        <br>
        <form @submit.prevent="addArticle" method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group col-6">
                    <label for="title">Titlu</label>
                    <input class="form-control" type="text" id="title" name="title" placeholder="Titlu" v-model="article.title" required="required">
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                </div>
                <div class="form-group col-6">
                    <label for="category">Categorie</label>
                    <select class="form-control" v-model="article.category" name="category" id="category">
                        <option v-for="(category, index) in availableCategories" :key="index" :value="index">{{ category }}</option>
                    </select>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-12">
                    <label for="content">Continut</label>
                    <textarea class="form-control" name="content" id="content" rows="15" v-model="article.content" placeholder="Continutul articolului..."></textarea>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-4">
                    <label for="est_time">Timp estimativ</label>
                    <input class="form-control" type="number" id="est_time" name="est_time" placeholder="Timp estimativ" v-model="article.est_time" required="required">
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                </div>
                <div class="form-group col-8">
                    <label for="main_image">Imagine principala</label>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">Upload</span>
                        </div>
                        <div class="custom-file">
                            <input type="file" name="main_image" class="custom-file-input" id="main_image" @change="processFile($event)">
                            <label class="custom-file-label" for="main_image">{{ this.article.main_image.name || "Alege imagine"}}</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-12">
                    <button class="btn btn-primary" type="submit">Adauga Articol</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        name: 'create-article',
        data() {
            return {
                availableCategories: [],
                article: {
                    title: "",
                    category: 1,
                    est_time: 1,
                    content: "",
                    main_image: "",
                },
            }
        },
        created: function () {
            this.getCategories();
        },
        methods: {
            /**
             * Get the available categories for the new article.
             */
            getCategories: function () {
                axios.get('/article/create/getCategories')
                    .then((response) => {
                        if (response.data.success) {
                            this.availableCategories = response.data.response;
                        } else {
                            console.log(response.data.response);
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    })
            },
            /**
             * Add the article.
             */
            addArticle: function () {
                if (!this.validateParameters(this.article)) {
                    return;
                }

                let formData = new FormData();

                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                };

                formData.append('title', this.article.title);
                formData.append('article_category', this.article.category);
                formData.append('content', this.article.content);
                formData.append('est_time', this.article.est_time);
                formData.append('main_image', this.article.main_image);

                axios.post('/article/create/saveArticle', formData, config)
                    .then(function (response) {
                        if (response.data.success) {
                            alert("Ok!");
                        } else {
                            console.log(response.data.response);
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            /**
             * Process the uploaded file.
             */
            processFile: function (event) {
                let file = event.target.files[0];

                if (this.isFileImage(file)) {
                    this.article.main_image = file;
                }
            },
            /**
             * Check if the uploaded file is an image.
             */
            isFileImage: function (file) {
                const acceptedImageTypes = ['image/jpeg', 'image/png'];

                return file && acceptedImageTypes.includes(file['type'])
            },
            /**
             * Validate the parameters before submiting the form.
             */
            validateParameters: function (parameters) {
                parameters.est_time = parseInt(parameters.est_time);

                if (parameters.title.length < 5) {
                    return false;
                }
                if (parameters.content.length <= 10) {
                    return false;
                }
                if (parameters.est_time <= 0 || (typeof parameters.est_time) !== 'number') {
                    return false;
                }

                return true;
            }
        }
    }
</script>

<style>

</style>