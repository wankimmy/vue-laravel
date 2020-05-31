<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">Users Table
                    <div class="card-tools">
                    <button class="btn btn-success" @click="newModal"> Add New
                    <i class="fa fa-user-plus"> </i> </button>
                    </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered" id="table">
        <thead>
        <tr>
           
            <th>No</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Date Created</th>
            <th>Action</th>
           
        </tr>
    </thead>
    <tbody>
    <tr v-for="user in users" :key="user.id">
    <td>{{ user.id }}</td>
    <td>{{ user.name }}</td>
    <td>{{ user.email }}</td>
    <td>{{ user.type | capitalize }}</td>
    <td>{{ user.created_at | theDate }}</td>
    <td>
        <a href="#" @click="editModal(user)">
        <i class="fa fa-edit text-green"> </i>
        </a>

        <a href="#" @click="deleteUser(user.id)">
        <i class="fa fa-trash text-red"> </i>
        </a>
    </td>
    </tr>
    </tbody>
    </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <form @submit.prevent="editmode ? updateUser() : createUser()">
      <div class="modal-header">
        <h5 class="modal-title" v-show="!editmode" id="exampleModalLabel">Add New User</h5>
        <h5 class="modal-title" v-show="editmode" id="exampleModalLabel">Update User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         
        <div class="form-group">
      <input v-model="form.name" type="text" name="name"
      placeholder="Name" 
        class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
      <has-error :form="form" field="name"></has-error>
    </div>

    <div class="form-group">
      <input v-model="form.email" type="text" name="email"
      placeholder="Email" 
        class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
      <has-error :form="form" field="email"></has-error>
    </div>
<div class="form-group">
                    <input v-model="form.bio" type="text" name="bio" class="form-control" placeholder="Bio" :class="{ 'is-invalid':form.errors.has('bio') }">
                    <has-error :form="form" field="bio"></has-error>
                </div>
                <div class="form-group">
                    <select name="type" v-model="form.type" id="type" class="form-control" :class="{ 'is-invalid':form.errors.has('type') }">
                        <option value="">Select User Role</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                        <option value="author">Author</option>
                    </select>
                    <has-error :form="form" field="type"></has-error>
                </div>
                <div class="form-group">
                    <input v-model="form.password" type="password" name="password" class="form-control" placeholder="Password" :class="{ 'is-invalid':form.errors.has('password') }">
                    <has-error :form="form" field="password"></has-error>
                </div>
              </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        <button v-show="editmode" type="submit" class="btn btn-primary">Update</button>
        <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
      </div>
  </form>
    </div>
  </div>
</div>

    </div>


</template>

<script>
    export default {

        data(){
            return{
                editmode: false,
                users: {},
                form: new Form({
                     id : '',
                    name : '',
                    email: '',
                    password: '',
                    type: '',
                    bio: '',
                    photo: '',
                })
            }
        },
         methods: {

            updateUser(){
                 this.$Progress.start();
                this.form.put('api/user/' + this.form.id).then(()=>{
                $('#exampleModal').modal('hide');    
                swal.fire(
                  'Update Success!',
                  'User has been updated.',
                  'success'
                )
                Fire.$emit('AfterUpdate');
                 this.$Progress.finish();
              
          }).catch(()=>{
            swal.fire(
                  'Failed!',
                  'There was something wrong.',
                  'warning'
                )
            this.$Progress.fail();
          });
            },

            newModal(){
                this.editmode = false;
                this.form.reset();
             $('#exampleModal').modal('show');
            },

            editModal(user){
                this.editmode = true;
                this.form.reset();
             $('#exampleModal').modal('show');
             this.form.fill(user);
            },

            deleteUser(id){
                this.$Progress.start();
                swal.fire({
              title: 'Are you sure?',
              text: "You won't be able to revert this!",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                 //send ajax request
                 if (result.value) {
                this.form.delete('api/user/' + id).then(()=>{
                swal.fire(
                  'Deleted!',
                  'Your file has been deleted.',
                  'success'
                )
                Fire.$emit('AfterDelete');
                this.$Progress.finish();
              
          }).catch(()=>{
            swal.fire(
                  'Failed!',
                  'There was something wrong.',
                  'warning'
                )
             this.$Progress.fail();
          });
            }

            })
            },
            loadUsers(){
                axios.get("api/user")
                    .then(({data}) => 
                        (this.users = data.data));
            },
            createUser(){


                this.$Progress.start();
                this.form.post('api/user').then(()=>{
               $('#exampleModal').modal('hide');
                // toast.fire({
                // icon: 'success',
                // title: 'User Created successfully'
                // })
                  swal.fire({
                  icon: 'success',
                  title: 'User Created Successfully',
                  // showConfirmButton: true,
                  // timer: 1500
                })
                Fire.$emit('AfterCreate');
                this.$Progress.finish();
              
          }).catch(()=>{
            swal.fire(
                  'Failed!',
                  'There was something wrong.',
                  'warning'
                )
             this.$Progress.fail();
          });
                
                    
               
            }
        },
        created() {
            this.loadUsers();
             Fire.$on('AfterCreate', () => {
                this.loadUsers();
            });
             Fire.$on('AfterDelete', () => {
                this.loadUsers();
            });
              Fire.$on('AfterUpdate', () => {
                this.loadUsers();
            })
        }
    }
</script>
