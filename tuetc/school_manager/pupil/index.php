<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location:../login.php');
    exit;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Học sinh</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8;" />
        <link href="../public/css/style.css" rel="stylesheet" type="text/css"/>
        <link href="../public/css/menu.css" rel="stylesheet" type="text/css"/>   
        <script src="../public/js/axios.js"></script>
        <script src="http://localhost/vue/dist/vue.min.js"></script>
        <style>
            table {
              border: 2px solid #42b983;
              border-radius: 3px;
              background-color: #fff;
            }

            th {
              background-color: #42b983;
              color: rgba(255,255,255,0.66);
              cursor: pointer;
              -webkit-user-select: none;
              -moz-user-select: none;
              -ms-user-select: none;
              user-select: none;
            }

            td {
              background-color: #f9f9f9;
            }

            th, td {
              min-width: 120px;
              padding: 10px 20px;
            }

            th.active {
              color: #fff;
            }

            th.active .arrow {
              opacity: 1;
            }

            .arrow {
              display: inline-block;
              vertical-align: middle;
              width: 0;
              height: 0;
              margin-left: 5px;
              opacity: 0.66;
            }

            .arrow.asc {
              border-left: 4px solid transparent;
              border-right: 4px solid transparent;
              border-bottom: 4px solid #fff;
            }

            .arrow.dsc {
              border-left: 4px solid transparent;
              border-right: 4px solid transparent;
              border-top: 4px solid #fff;
            }
            
            input[type='text']{
                width: 100%;
            }
        </style>
    </head>
    <body>
        <?php
        include_once '../menu.php';
        ?>
        <script type="text/x-template" id="grid-template-pupil">
          <table v-if="filteredData.length">
            <thead>
              <tr>
                <th v-for="key in columns" v-if="key!='id'"
                  @click="sortBy(key)"
                  :class="{ active: sortKey == key }">
                  {{ key | label }}
                  <span class="arrow" :class="sortOrders[key] > 0 ? 'asc' : 'dsc'">
                  </span>
                </th>
                <th>&nbsp;</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="entry in filteredData">
                <td v-for="key in columns" v-if="key!='id'" v-html="entry[key]">
                </td>
                <td style="text-align: center;">
                        
                    <img @click="deletePupil(entry['id'])" :id="entry['id']" class="delete" style="margin-right: 20px;" title="Nhấn vào đây để xóa" src="../public/images/delete-icon.png"/>

                    <img @click="id=entry['id'];window.location='edit.php?id='+id;" title="Nhấn vào đây để sửa" src="../public/images/ico_edit.png"/>

                </td>
              </tr>
            </tbody>
          </table>
          <p v-else>Không có kết quả.</p>
        </script>
        <div id="div">
            <div class="right toolbar">
                <input @click="window.location = 'add.php';" type="button" value="Thêm mới" class="button">
            </div>
            <div style="clear: both;"></div>
            <form id="pupil">
                <input type="text" placeholder="nhập vào đây để tìm kiếm tất cả" name="q" v-model="q">
            </form>
            <form>
                <input type="text" placeholder="nhập vào đây để tìm kiếm theo họ tên" name="full_name" v-model="full_name">
            </form>
            <pupil-grid
                :data="gridData"
                :columns="gridColumns" 
                :filter-key-fullname="full_name"
                :filter-key="q">
              </pupil-grid> 
        </div>
        

        <script type="text/javascript">
            Vue.component('pupil-grid', {
              template: '#grid-template-pupil',
              replace: true,
              props: {
                data: Array,
                columns: Array,
                filterKey: String,
                filterKeyFullname: String
              },
              data: function () {
                var sortOrders = {}
                this.columns.forEach(function (key) {
                  sortOrders[key] = 1
                })
                return {
                  sortKey: '',
                  sortOrders: sortOrders
                }
              },
              computed: {
                filteredData: function () {
                  var sortKey = this.sortKey
                  var filterKey = this.filterKey.trim() && this.filterKey.trim().toLowerCase()
                  var filterKeyFullname = this.filterKeyFullname.trim() && this.filterKeyFullname.trim().toLowerCase()
                  var order = this.sortOrders[sortKey] || 1
                  var data = this.data
                  if (filterKey) {
                    data = data.filter(function (row) {
                      return Object.keys(row).some(function (key) {
                        if(key!='id'&&key!='avatar'){
                              return String(row[key]).toLowerCase().indexOf(filterKey) > -1
                          }
                      })
                    })
                  }
                  else if (filterKeyFullname) {
                    data = data.filter(function (row) {
                      return Object.keys(row).some(function (key) {
                        if(key=='full_name'){
                              return String(row[key]).toLowerCase().indexOf(filterKeyFullname) > -1
                          }
                      })
                    })
                  }
                  if (sortKey) {
                    data = data.slice().sort(function (a, b) {
                      a = a[sortKey]
                      b = b[sortKey]
                      return (a === b ? 0 : a > b ? 1 : -1) * order
                    })
                  }
                  return data
                }
              },
              filters: {
                label: function (str) {
                    if(str=='name'){
                        return 'Lớp';
                    }
                    else if(str=='full_name'){
                        return 'Họ và tên';
                    }
                    else if(str=='birthday'){
                        return 'Ngày sinh';
                    }
                    else if(str=='sex'){
                        return 'Giới tính';
                    }
                    else if(str=='married'){
                        return 'Tình trạng hôn nhân';
                    }
                    else if(str=='avatar'){
                        return 'Ảnh đại diện';
                    }
                    else if(str=='introduce'){
                        return 'Vài nét về bản thân';
                    }
                    else{
                        return str;
                    }
                },
                capitalize: function (str) {
                  return str.charAt(0).toUpperCase() + str.slice(1)
                }
              },
              methods: {
                sortBy: function (key) {
                  this.sortKey = key
                  this.sortOrders[key] = this.sortOrders[key] * -1
                },
                deletePupil: function (id) {
                    axios.get('../common/delete.php?id='+id+'&table_name=pupil')
                      .then(function(response){                        
                      });  
                    for(i=0;i<this.data.length;i++){
                        if(this.data[i].id==id){
                            this.data.splice(i,1);
                            break;
                        }
                    }
                }
              }
            })
            
            var table = new Vue({
                el: '#div',
                data: {
                    q: '',
                    full_name:'',
                    gridColumns: ['name','full_name','birthday','sex','married','avatar','introduce','id'],
                    gridData: [
                      <?php 
                      $i=0;
                      include '../define.php';
                    $conn = mysqli_connect(HOST, USERNAME, PASSWORD, DB_NAME) or die();
                    mysqli_query($conn, "set names 'utf8'");
                    $result = mysqli_query($conn, "select * from pupil_full order by class_id ASC");
                      while ($row = mysqli_fetch_array($result)) {
                          echo "{ name: '".$row['name'].
                                  "',full_name: '".html_entity_decode($row['full_name']).
                                  "',birthday: '".convertToVNDate($row['birthday']).
                                  "',sex: '".($row['sex']=='1'?'nam':'nữ').
                                  "',married: '".($row['married']=='1'?'đã kết hôn':'độc thân').
                                  "',avatar: '".(trim($row['avatar'])!=''?'<img src="../public/images/database/avatar/'.$row['avatar'].'" style="width: 50px;height: 50px;"/>':'').
                                  "',id: '".$row['id'].
                                  "',introduce: '".html_entity_decode($row['introduce'])."' }";
                          if($i< mysqli_num_rows($result)){
                              echo ",";
                          }
                          $i++;
                      }
                      ?>
                    ]
                  }
            });
        </script>
    </body>
</html>

<?php

function convertToVNDate($dateTime) {
    $temp = explode(' ', $dateTime);
    $dateEn = $temp[0];
    $temp = explode('-', $dateEn);
    $dateVn = $temp[2] . '/' . $temp[1] . '/' . $temp[0];
    return $dateVn;
}
?>