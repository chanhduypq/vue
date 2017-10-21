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
        <title>Lớp học</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8;" />
        <link href="../public/css/style.css" rel="stylesheet" type="text/css"/>
        <link href="../public/css/menu.css" rel="stylesheet" type="text/css"/> 
        <script src="../public/js/jquery-2.0.3.js"></script>
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
        </style>
    </head>
    <body>
        <?php 
        include_once  '../menu.php';
        ?>
        <script type="text/x-template" id="grid-template-class">
          <table v-if="filteredData.length">
            <thead>
              <tr>
                <th v-for="key in columns" v-if="key!='id'"
                  @click="sortBy(key)"
                  :class="{ active: sortKey == key }">
                  {{ key | capitalize }}
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
                        
                    <img v-if="entry['count_pupil']=='0'" @click="deleteClass(entry['id'])" :id="entry['id']" class="delete" style="margin-right: 20px;" title="Nhấn vào đây để xóa" src="../public/images/delete-icon.png"/>

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
            <form id="class">
                <input placeholder="nhập vào đây để tìm kiếm" name="q" v-model="q">
            </form>
            <class-grid
                :data="gridData"
                :columns="gridColumns"
                :filter-key="q">
              </class-grid>       

        </div>
        

        
        <script type="text/javascript">
            Vue.component('class-grid', {
              template: '#grid-template-class',
              replace: true,
              props: {
                data: Array,
                columns: Array,
                filterKey: String
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
                  var filterKey = this.filterKey && this.filterKey.toLowerCase()
                  var order = this.sortOrders[sortKey] || 1
                  var data = this.data
                  if (filterKey) {
                    data = data.filter(function (row) {
                      return Object.keys(row).some(function (key) {
                          if(key!='id'){
                              return String(row[key]).toLowerCase().indexOf(filterKey) > -1
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
                capitalize: function (str) {
                    if(str=='count_pupil'){
                        return 'Số học sinh';
                    }
                    else if(str=='class_name'){
                        return 'Tên lớp';
                    }
                    else{
                        return str;
                    }
//                  return str.charAt(0).toUpperCase() + str.slice(1)
                }
              },
              methods: {
                sortBy: function (key) {
                  this.sortKey = key
                  this.sortOrders[key] = this.sortOrders[key] * -1
                },
                deleteClass: function (id) {    
                    $.ajax({
                       url:'../common/delete.php?id='+id+'&table_name=class'
                    });
                    $("#"+id).parent().parent().remove();
                }
              }
            })
            
            var table = new Vue({
              el: '#div',
              data: {
                q: '',
                gridColumns: ['class_name','id','count_pupil'],
                gridData: [
                  <?php 
                  $i=0;
                  include '../define.php';
                $conn = mysqli_connect(HOST, USERNAME, PASSWORD, DB_NAME) or die();
                mysqli_query($conn, "set names 'utf8'");
                $result = mysqli_query($conn, "SELECT name,id,(SELECT count(*) from pupil WHERE class_id=class.id) as count_pupil FROM class");
                  while ($row = mysqli_fetch_array($result)) {
                      echo "{ class_name: '".$row['name']."',id:'".$row['id']."',count_pupil:'".$row['count_pupil']."' }";
                      if($i< mysqli_num_rows($result)){
                          echo ",";
                      }
                      $i++;
                  }
                  ?>
                ]
              },
              methods: {
                togglePupil: function (id) {    
                    if ($('#'+id).attr('src').indexOf('down') != -1) {
                        src = $('#'+id).attr('src');
                        src = src.replace('down', 'up');
                    } else {
                        src = src.replace('up', 'down');
                    }

                    $('#'+id).attr('src', src);
                    
                    $.ajax({
                       url:'../load_pupil.php?class_id='+id,
                       success: function(data) {
                           $('#'+id).parent().parent().find('div.list_pupil').html(data).slideToggle();
                      }

                   });
                }
              }
            }); 
            

        </script>
        
    </body>
    
</html>