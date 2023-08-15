<div class="container container-one">
    <div class="container-inner">
        <div></div>
       <div>
           <h1>Data made simple</h1>
           <p>DataDesk is a data collection, management and distribution platform built by Media Hack Collective for African users and newsrooms. DataDesk is built around the constraints that exist in African newsrooms and is focused on making data collection as simple as possible. </p>
           <p></p>
</div>

    </div>
</div>


<div class="container container-two">
    <div class="container-inner container-inner-two">
       
       <div class="feature">
          <h2>Easy to use</h2>
           <p>DataDesk is built with ease-of-use in mind. Data can be uploaded directly from Google Sheets or as CSV files. Data is immediately available for download in CSV or JSON format. Simple tagging, meta data, bookmarks and search makes it easy to sort and find datasets.  </p>
           <p></p>
</div>

<div class="feature">
          <h2>Point-and-click power</h2>
           <p>DataDesk is designed around tidy data principles and has built-in transformations such as merging multiple tables, pivoting tables and a query-builder to create new views of tables. Transformations are as simple as possible and don't require any data programming skills. Transformations are point-and-click easy. </p>
           <p></p>
</div>

    </div>
</div>

<div class="container container-three">
    <div class="container-inner container-inner-three">
        
       <div class="feature">
           <h2>Built for journalists</h2>
           <p>DataDesk was built by Media Hack Collective (MHC), a South Africa-based data journalism publisher. DataDesk was funded in part by the Google News Initiative via an innovation grant in 2022. MHC has been active in the Africa data journalism space for more than 5 years and has developed DataDesk based on lessons learned during this time. </p>
           <p></p>
</div>

    </div>
</div>

<div class="container container-four">
    <div class="container-inner-four">
        Built by <a href="https://mediahack.co.za">Media Hack Collective</a>, funded by the <a href="https://newsinitiative.withgoogle.com/" target="_blank">Google News Initiative</a href="https://newsinitiative.withgoogle.com/" target="_blank">, <a href="https://github.com/mediahackza/datadesk" target="_blank">open source</a>. <br/>
        To find out more contact us at <a href="mailto:datadesk@mediahack.co.za">datadesk@mediahack.co.za</a>
    </div>
</div>

<?php
include_once('components/html_footer.php')
?>

<style>

body { 

    background: url('<?php echo $base . "/assets/images/keyboard.jpg"; ?>') no-repeat center center fixed; 
  -webkit-background-size: cover;
  -moz-background-size: cover;
  -o-background-size: cover;
  background-size: cover;
 
}
    .container { 
        width: 100%;
        /* max-width: 800px;  */
        margin: 0 auto;
        /* background: #00a5a2; */
        grid-gap: 20px;
        font-size: 1.1rem;

        
    }
    .container-one { 
        min-height: 80vh;
    }
    .container img { 
        width: 300px;
    }
    .container-inner { 
        width: 90%; 
        max-width: 1000px; 
        display: grid;
        grid-template-columns: 1fr 1fr;
        margin: 0 auto;
        padding-top: 100px; 
        padding-bottom: 100px;
        color: #fff;
 
        line-height: 1.5;
        height: 100%;
    }
    nav { 
        margin-bottom: 0px;
        background: #ffffff;
        border: none;
        color: #fff;
    }
    .container-inner-two { 
        grid-template-columns: 1fr 1fr;
        color: #000;
        padding-top: 100px;
        padding-bottom: 20px;

        grid-gap: 50px;
    }
    .container-two { 
        background: #00000099;
    }
    .feature { 
        /* background: #fff; */
        color: #fff;
        padding: 10px 30px;
        border: solid 1px lightgray;
        background: #ffffff20;
    }
    .container-inner-three { 
        padding-top: 30px;
        grid-template-columns: 1fr;
        padding-bottom: 30px;
        color: #fff;
        
        
        
    }
    .container-three { 
        background: #00000099;
        color: #fff;

    }
    .container-four { 
        padding: 30px; 
        background: #000; 
        color: #fff; 
        text-align: center;
        line-height: 1.5;
        font-size: 0.9rem;


    }
    .container-inner-four { 
    
    }
    .container-four a { 
        color: #fff;
    }
    .account a { 
        background: #8D2792;
        color: #fff;
        padding: 15px 30px;
        fot-size: 1.2rem;
        text-transform: uppercase;
        text-decoration: none;
        font-weight: 700;
        border-radius: 3px;
    }
    .account a:hover { 
        background: #b532bc;
    }
</style>