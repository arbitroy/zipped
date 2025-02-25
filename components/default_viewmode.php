 <form class="row g-3 builderForm totallyHide viewMode" id="birthday-viewMode">
     <div>

         <div class="row mt-1 mx-2 my-2">
             <div class="text-start mb-2 col-9">
                 <h6 class="fw-normal fs-6">Birth Story</h6>
             </div>
             <div class="text-end mb-2 col-3">
                 <i class="bi bi-x text-white fs-4" onclick="saveAndClose()"></i>
             </div>
         </div>
         <div class="row mt-1 mx-2 my-2 memtabs">
             <div class="text-start d-flex align-items-center justify-content-center col-4 memtab memtab-active" onclick="activateTab(this)" tag="birth-remember-view">
                 <h6 class="fw-normal fs-6">Memory</h6>
             </div>
             <div class="text-center d-flex align-items-center justify-content-center col-4 memtab" onclick="activateTab(this)" tag="birth-history-view">
                 <h6 class="fw-normal fs-6">History</h6>
             </div>
             <div class="text-end d-flex align-items-center justify-content-center col-4 memtab" onclick="activateTab(this)" tag="birth-collab-view">
                 <h6 class="fw-normal fs-6">Viewers</h6>
             </div>
         </div>
         <div class="col-12 mb-2 tabgroup">
             <div class="tabsect birth-remember-view">
                 <span class="mb-2 text-white " id="name-label"><b>Brian Stephen Ashbaugh</b> was born on November 14, 1968 in Pontiac, MI (USA) to Donald
                     Fred Ashbaugh and Norma June Ashbaugh.</span>
                 <div class="col-12 mb-3 mt-4">
                     <h6>Memory:</h6>
                     <p>
                         Dad was in the U.S. Army at the time and stationed in Anchorage, Alaska. Mom went home to Michigan to stay with her parents
                         and have me in a hospital there (because she didn’t trust the Army hospital!).
                         I arrived at 6 am Tuesday morning, weighing 7 lbs 3 oz and measuring 19 inches long.
                     </p>

                 </div>

                 <div class="col-12 mb-3 mt-4">
                     <h6>From Don Ashbaugh:</h6>
                     <p>
                         I didn’t see Brian for 6 months until June was ready to fly back to Alaska with me. We lived there for about a year.
                     </p>

                 </div>

             </div>

             <div class="tabsect birth-history-view totallyHide">

                 <div class="col-12 mb-3 mt-4">
                     <h6>On this day in history: </h6>
                     <ul>
                         <li>French impressionist painter Claude Monet was born. (1840)</li>
                         <li>The BBC began daily radio broadcasts, starting with a news bulletin and a weather forecast.(1922)</li>
                         <li>King Charles the III of the United Kingdom was born. (1948)</li>
                     </ul>

                 </div>

                 <div class="col-12 mb-3 mt-4">
                     <h6>In 1968: </h6>
                     <ul>
                         <li>Gasoline sold for 50 cents</li>
                         <li>Richard Nixon was the newly elected President of the U.S.</li>
                         <li>“Hey Jude” by the Beatles was the #1 song in the U.S.</li>
                         <li>Dr. Martin Luther King was assassinated.</li>
                         <li>US President Lyndon B. Johnson signs the 1968 Civil Rights Act.</li>
                         <li>Nasa launches the Apollo 8 mission.</li>
                         <li>Wilt Chamberlain becomes 1st NBAer to score 25,000 points.</li>
                         <li>IXX Summer Olympic Games open at Mexico City, making Mexico the first Latin American country to host the Olympics.</li>
                     </ul>

                 </div>
             </div>
             <div class="tabsect birth-collab-view totallyHide">
                 <div class="col-12 mb-3 mt-4">
                     <h6>Who can see this memory: </h6>
                     <p>Family</p>

                 </div>

                 <div class="col-12 mb-3 mt-4">
                     <h6>Who contributed stories: </h6>
                     <p>Don Ashbaugh</p>

                 </div>
             </div>

         </div>

         <div class="row mt-1 mx-2 my-2 position-relative">
             <div class="text-center mb-2 col-12">
                 <button type="button" class="btn btn-white" onclick="saveAndClose('birthday')">
                     Close
                     <i class="bi bi-x"></i>
                 </button>
             </div>
         </div>

     </div>
 </form>