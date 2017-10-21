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
        include_once '../menu.php';
        ?>
        <script type="text/x-template" id="grid-template-pupil">
          <table v-if="filteredData.length">
            <thead>
              <tr>
                <th v-for="key in columns"
                  @click="sortBy(key)"
                  :class="{ active: sortKey == key }">
                  {{ key | capitalize }}
                  <span class="arrow" :class="sortOrders[key] > 0 ? 'asc' : 'dsc'">
                  </span>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="entry in filteredData">
                <td v-for="key in columns">
                  {{entry[key]}}
                </td>
              </tr>
            </tbody>
          </table>
          <p v-else>No matches found.</p>
        </script>
        <div id="div">
            <div class="right toolbar">
                <input @click="window.location = 'add.php';" type="button" value="Thêm mới" class="button">
            </div>
            <form id="pupil">
                <input placeholder="nhập vào đây để tìm kiếm" name="q" v-model="q">
            </form>
            <pupil-grid
                :data="gridData"
                :columns="gridColumns"
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
                        return String(row[key]).toLowerCase().indexOf(filterKey) > -1
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
                  return str.charAt(0).toUpperCase() + str.slice(1)
                }
              },
              methods: {
                sortBy: function (key) {
                  this.sortKey = key
                  this.sortOrders[key] = this.sortOrders[key] * -1
                }
              }
            })
            
            var table = new Vue({
                el: '#div',
                data: {
                    q: '',
                    gridColumns: ['name','full_name','birthday','sex','married','avatar','introduce'],
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
                                  "',avatar: '".$row['avatar'].
                                  "',introduce: '".html_entity_decode($row['introduce'])."' }";
                          if($i< mysqli_num_rows($result)){
                              echo ",";
                          }
                          $i++;
                      }
                      ?>
                    ]
                  },
                methods: {
                    deletePupil: function (id) {
                        $.ajax({
                            url: '../common/delete.php?id=' + id + '&table_name=pupil'
                        });
                        $("#" + id).parent().parent().remove();
                    }
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