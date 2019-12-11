<template>
    <div class="container p-0">

      <div class="text-right w-100">
        <button @click="loadCreateForm" type="button" class="btn btn-primary disabled mt-3">Create New Page</button>
      </div>

      <!--一覧画面-->
      <div v-show="pagesShow" class="row">
            <!--card-->
            <div v-for="(page,index) in pages" v-bind:key="page.id" class="post text-left col-md-6 pr-md-2 mt-3">
                <div class="border h-100 p-4">                   
                    <h4>{{page.title}}</h4>
                    <p class="created-date text-right">{{page.created_at}}</p>
                    <hr>
                    <p>{{page.content}}</p>
                    <div class="text-right">
                        <button @click="loadViewModal(index)" class="btn btn-outline-primary disabled">View</button>
                        <button @click="loadEditForm(index)" class="btn btn-outline-primary disabled ml-2">Edit</button>
                        <button @click="deleteTask(index)" class="btn btn-outline-danger disabled ml-2">Delete</button>
                    </div>
                </div>
            </div>
            <!--END : card-->
      </div>
      <!--END : 一覧画面-->

      <!--詳細表示モーダル-->
      <div id="view-modal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{{detail.title}}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-left">
                    <p>{{detail.content}}</p>
                </div>
            </div>
        </div>
      </div>
      <!--END : 詳細表示モーダル-->

      <!--作成フォーム画面-->
      <div v-show="createShow" class="create text-left col-8 mt-5">
        <form>
                <div class="form-group">
                    <label for="title">Title</label>
                    <input v-model="page.title" type="text" class="form-control" id="title">
                </div>

                <div class="form-group">
                    <label>Content</label>
                    <textarea v-model="page.content" class="form-control" rows="3" id="content"></textarea>
                </div>

                <div class="text-right">
                    <button @click="closeCreateForm" type="button" class="btn btn-secondary disabled">Close</button>
                    <button @click="createPage" type="button" class="btn btn-primary disabled">Save</button>
                </div>
        </form>

        <div class="alert alert-danger" v-if="errors.length>0">
            <ul>
                <li v-for="error in errors">{{error}}</li>
            </ul>
        </div>
      </div>
      <!--END : 作成フォーム画面-->

      <!--編集フォーム画面-->
      <div v-show="editShow" class="create text-left col-8 mt-5">
        <form>
                <div class="form-group">
                    <label for="title">Title</label>
                    <input v-model="edit_task.title" type="text" class="form-control" id="title">
                </div>

                <div class="form-group">
                    <label>Content</label>
                    <textarea v-model="edit_task.content" class="form-control" rows="3" id="content"></textarea>
                </div>
                <div class="text-right">
                    <button @click="closeEditForm" type="button" class="btn btn-secondary disabled">Close</button>
                    <button @click="editPage" type="button" class="btn btn-primary disabled">Save changes</button>
                </div>
        </form>

        <div class="alert alert-danger" v-if="errors.length>0">
            <ul>
                <li v-for="error in errors">{{error}}</li>
            </ul>
        </div>
      </div>
      <!--END : 編集フォーム画面-->

    </div>
</template>

<script>
    export default {
        data(){
            return{
                page:{
                    title: '',
                    content: '',
                },
                pages: [],
                detail: [],
                edit_task: [],
                uri: 'http://127.0.0.1:8000/pages/',
                errors: [],
                pagesShow: true,
                createShow: false,
                editShow: false
            }
        },
        methods: {

            /*Read Methods(All)*/
            loadTasks(){
                axios.get(this.uri).then(response=>{
                    this.pages = response.data.pages;
                });
            },
            /*END : Read Methods(All)*/

            /*Read Methods(Detail)*/
            loadViewModal(index){
                this.detail = this.pages[index];
                $('#view-modal').modal('show');
            },
            /*END : Read Methods(Detail)*/

            /*Create Methods*/
            loadCreateForm(){
                this.pagesShow = false;
                this.createShow = true;
            },
            createPage(){
                axios.post(this.uri, {title:this.page.title, content:this.page.content}).then(response=>{
                    this.pages.push(response.data.page);
                    this.resetData();
                    this.closeCreateForm();
                }).catch(error=>{
                    this.errors = [];
                    if (error.response.data.errors.title){
                        this.errors.push(error.response.data.errors.title[0]);    
                    }
                    if (error.response.data.errors.title){
                        this.errors.push(error.response.data.errors.content[0]);    
                    }
                });
                
            },
            closeCreateForm(){
                this.createShow = false;
                this.pagesShow = true;
            },
            /*END : Create Methods*/

            /*Edit Methods*/
            loadEditForm(index){
                this.edit_task = this.pages[index];
                this.pagesShow = false;
                this.editShow = true;
            },
            editPage(){
                axios.post(this.uri + 'edit/' + this.edit_task.id, 
                           {title:this.edit_task.title, content:this.edit_task.content})
                     .then(response=>{
                           this.closeEditForm();
                }).catch(error=>{
                    this.errors = [];
                    if (error.response.data.errors.title){
                        this.errors.push(error.response.data.errors.title[0]);    
                    }
                    if (error.response.data.errors.title){
                        this.errors.push(error.response.data.errors.content[0]);    
                    }
                });
                
            },
            closeEditForm(){
                this.editShow = false;
                this.pagesShow = true;
            },
            /*END : Edit Methods*/

            /*Delete Methods*/
            deleteTask(index){
                let confirmBox = confirm('ページを削除しますか？');
                if (confirmBox){
                    axios.delete(this.uri+'delete/'+this.pages[index].id)
                         .then(response=>{
                             this.$delete(this.pages,index);
                         }).catch(error=>{
                             console.log("投稿が削除できませんでした")
                         });
                }
            },
            /*END : Delete Methods*/

            resetData(){
                this.page.title = '';
                this.page.content = '';
            }
        },
        mounted() {
            this.loadTasks();
        }
    }
</script>

<style>
.post{
  height:200px; 
  color: #444; 
}

.modal{
  color: #444;    
}

.created-date{
    font-size: 12px;
}

.create{
    margin: auto;
}
</style>