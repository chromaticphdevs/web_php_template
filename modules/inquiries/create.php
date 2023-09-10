<?php

use function PHPSTORM_META\map;

 build('content') ?>
<?php
    $clientService = new ClientService();
    $clientMessages = $clientService->getAll([
        'where' => [
            'client.agentcode' => whoIs('usercode'),
            'client.recno' => 1 
        ]
    ]);
?>
    <!-- Catalogue -->
<div class="card">
    <!-- Catalogue Result-->
    <div class="card-body p-4">
        <h4>My Inquiries</h4>
        <div class="row">
            <div class="col-sm-4">
                <ul class="list-group text-left">
                    <li onclick="" class="list-group-item list-group-item-info bg-col1 text-white">
                        <div class="d-flex">
                            <div class="align-middle align-self-center flex-grow-1 fs-5">My Clients</div>
                        </div>
                    </li>
                    <li onclick="" class="list-group-item">
                        <br>
                        <div class="d-flex">
                            <div class="align-middle align-self-center">
                                <div class="input-group mb-3">
                                    <input id="word" type="text" class="form-control" placeholder="Search" aria-label="Search" aria-describedby="input-group-button-right">
                                    <button id="find_names" type="button" class="btn btn-warning" id="input-group-button-right"><i class="fa fa-search"></i></button>
                                </div>
                                <div class="form-check form-check-inline">
                                <input class="form-check-input mychk" type="checkbox" value="" id="par2" checked="">
                                <label class="form-check-label" for="par2">Lead</label>
                                </div>
                                <div class="form-check form-check-inline">
                                <input class="form-check-input mychk" type="checkbox" value="" id="par1">
                                <label class="form-check-label" for="par1">Keep</label>
                                </div>
                                <div class="form-check form-check-inline">
                                <input class="form-check-input mychk" type="checkbox" value="" id="par3">
                                <label class="form-check-label" for="par3">Blacklist</label>
                                </div>
                                <div class="form-check form-check-inline">
                                <input class="form-check-input mychk" type="checkbox" value="" id="par4">
                                <label class="form-check-label" for="par4">Asnwered</label>
                                </div>
                            </div>
                        </div>
                        <br>
                    </li>
                </ul>
                <br>
                <ul id="loadclienthere" class="list-group text-left">
                    <li onclick='loadclientproperty(`$email`,$a)' class='list-group-item text-secondary list-group-item-action'>
                        <div class='d-flex'>
                            <i class='fa fa-user me-2 align-middle align-self-center text-$color'></i>
                            <span class='align-middle align-self-center flex-grow-1'>Mark Gonzales test</span>
                            <span class='align-middle align-self-center'><i class='fa fa-caret-right'></i></span>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="col-sm-8">
                <!-- Chat box -->
                <div class="card">
                    <div class="card-body">
                        <div id="loadclientproperty" class="d-flex mb-3">
                            <i class="fa fa-2x fa-user me-2 align-middle align-self-center text-secondary"></i>
                            <div class="align-middle align-self-center flex-grow-1">
                                <span class="">Client</span>
                            </div>

                        </div>
                        <div id="loadclientmessages" class="border bg-light p-3 mb-3" 
                            style="max-height: 500px; overflow: hidden; overflow-y: auto;">
                            <?php if(empty($clientMessages)) :?>
                                <p>no messages</p>
                            <?php else :?>
                                <?php foreach($clientMessages as $key => $row) : ?>
                                    <?php 
                                        $lastUpdate = time_since($row['dateinserted']);
                                        $clientMessage = "{$row['adstitle']}({$row['listingcode']})";
                                    ?>
                                    <div class='mb-3 d-flex flex-row'>
                                        <div class='bg-white border p-3 rounded w-100'>
                                            <div class='form-check form-check-inline w-100'>
                                                <input class='form-check-input showhidecheck' type='checkbox' id='ch_<?php echo $row['recno']?>' 
                                                data=<?php echo $row['recno']?> $checkbox>
                                                <label class='form-check-label' for='ch_<?php echo $row['recno']?>'>
                                                    <span class='fw-bold'><?php echo $row['clientremarks']?></span>
                                                </label>
                                                <div class="mt-2 mb-2">
                                                    <div>LINK : <?php echo wLinkDefault(_route('prop_detail', [
                                                        'propId' => seal($row['fk_ads_key'])
                                                    ]), $clientMessage)?></div>

                                                    <div>TYPE : <?php echo $row['listtypecode']?></div>
                                                    <div>PRICE : <?php echo $row['price']?></div>
                                                    <div>TERMS : <?php echo $row['paymentterm']?></div>
                                                </div>
                                                <small class='text-muted'>Posted by: 
                                                        <?php echo $row['clientfname'] . ' ' .$row['clientlname']?> <br><?php echo $lastUpdate?></small><br>
                                                <hr>
                                                <p class=''><?php echo $row['agent_notes']?></p>
                                                <button type='button' class='btn btn-sm btn-link float-end text-secondary' 
                                                data-bs-toggle='modal' data-bs-target='#add_notes'>Add Notes</button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach?>
                            <?php endif?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endbuild()?>
<?php loadTo()?>