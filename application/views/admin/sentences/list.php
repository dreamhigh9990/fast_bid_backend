<div class="page-content">
    <div class="content">
        <div clas="page-title">
            <h3>
                Sentences
            </h3>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="grid simple">
                    <div class="grid-body">
                        <table class="table" id="sentences-table">
                            <thead>
                            <tr>
                                <th style="width:5%">#</th>
                                <th style="width:30%">Source</th>
                                <th style="width:30%">Target</th>
                                <th style="width:5%">Direction</th>
                                <th style="width:10%">Image</th>
                                <th style="width:10%">Audio</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (count($sentences)) {
                                $num = 1;
                                foreach ($sentences as $row) {
                                    echo '<tr id="sentence-' . $row['sentences_id'] . '">';
                                    echo '<td>' . $num . '</td>';
                                    $s_language_field = "";
                                    switch ($row['s_language']) {
                                        case 'en':
                                            $s_language_field = 's_en';
                                            break;
                                        case 'zh-CN':
                                            $s_language_field = 's_zh-CN';
                                            break;
                                        default:
                                            $s_language_field = 's_de';
                                            break;
                                    }
                                    $d_language_field = "";
                                    switch ($row['d_language']) {
                                        case 'en':
                                            $d_language_field = 'd_en';
                                            break;
                                        case 'zh-CN':
                                            $d_language_field = 'd_zh-CN';
                                            break;
                                        default:
                                            $d_language_field = 'd_de';
                                            break;
                                    }
                                    echo '<td class="source">' . $row[$s_language_field] . '</td>';
                                    echo '<td class="target">' . $row[$d_language_field] . '</td>';
                                    echo '<td class="direction">' . $row['s_language'] . '->' . $row['d_language'] . '</td>';
                                    echo '<td class="image">';
                                    if (isset($row['image']))
                                        echo '<img width="40px" height="40px" src="' . base_url() . 'upload/sentences/image/' . $row['image'] . '">';
                                    echo '</td>';
                                    echo '<td class="audio">';
                                    if (isset($row['audio']))
                                        echo '<video width="250px" height="30" controls><source src="' . base_url() . 'upload/sentences/audio/' . $row['audio'] . '" type="video/mp4"/></video>';
                                    echo '</td>';
                                    echo '<td class="crud-actions">
                              <a href="javascript:;" rel="' . $row['sentences_id'] . '" class="ajaxEdit"><img src="' . base_url() . 'assets/img/icon/edit.png" class="eimage"></a>
                              <a href="javascript:;" rel="' . $row['sentences_id'] . '" class="ajaxDelete"><img src="' . base_url() . 'assets/img/icon/remove.png" class="dimage"></a>
                            </td>';
                                    echo '</tr>';
                                    $num++;
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="admin-bar" id="quick-access" style="display:">
        <div class="admin-bar-inner">
            <table class="table">
                <tbody>
                <tr>
                    <td style="width:25%;border: none" class="ajaxReq">
                        <textarea name="source" rows="5" placeholder="Enter source sentence"></textarea>
                    </td>
                    <td style="width:25%;border: none" class="ajaxReq">
                        <textarea name="target" rows="5" placeholder="Enter target sentence"></textarea>
                    </td>
                    <td style="width:10%;border: none" class="ajaxReq">
                        <select name="direction" value="EN->CN">
                            <option value="EN->CN">EN->CN</option>
                            <option value="EN->GE">EN->GE</option>
                            <option value="CN->EN">CN->EN</option>
                            <option value="GE->EN">GE->EN</option>
                        </select>
                    </td>
                    <td style="width:10%;border: none" class="ajaxReq"><input type="file" accept="image/*" name="image"
                                                                              placeholder=""></td>
                    <td style="width:10%;border: none" class="ajaxReq"><input type="file" accept="audio/*" name="audio"
                                                                              placeholder=""></td>
                    <td class="crud-actions" style="border: none">
                        <button class="ajaxSave btn btn-primary btn-cons btn-add" type="button">Add Sentence</button>
                        <button class="cancel btn btn-white btn-cons btn-cancel" type="button">Cancel</button>
                    </td>
                </tr>
                </tbody>
            </table>

        </div>
    </div>
    <div class="addNewRow"></div>
</div>
