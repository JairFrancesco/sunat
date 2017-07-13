	$scope.ver = function(doc,cab,det,rela,adcab,addet,leye){	
		$scope.doc = doc;


		$scope.num_doc = doc[1];
		$scope.cla_doc = doc[4];
		$scope.co_cr_an = doc[5];
		$scope.exi_fra = doc[6];
		$scope.tip_imp = doc[7];
		$scope.moneda = doc[9];
		$scope.anu_sn = doc[12];
		$scope.doc_anu = doc[13];


		$scope.cab = cab;
		$scope.det = det;		
		$scope.rela = rela;
		$scope.adcab = adcab;
		$scope.addet = addet;
		$scope.leye = leye;
		


		// Factura o Boleta
		if ($scope.cla_doc=='FS' || $scope.cla_doc=='FR' || $scope.cla_doc=='BS' || $scope.cla_doc=='BR' || $scope.cla_doc=='FC'){ // Factura y Boleta			


			// cabezera			
			$http.get( '/apis/fbc/'+$scope.gen+'/'+$scope.emp+'/'+$scope.num_doc).success(function(data){
				$scope.fb_cab = data[0];
							
				// genera txt cabezera								
				if($scope.cab=='S'){
					var i=0;
					var cabe = '';
					data[0].forEach(function(entry) {
						if(i<=16){
							cabe += entry+'|';							
						}
	    				i++;
					});
					var cabezera = new File([cabe], { type: 'text/plain;charset=utf-8' });
					FileSaver.saveAs(cabezera, data[0][17]);	
				}


				// detalle
				if($scope.co_cr_an=='CO' || $scope.co_cr_an=='CR'){				
					if ($scope.tip_imp=='D'){
						$http.get( '/apis/dds/'+$scope.gen+'/'+$scope.emp+'/'+$scope.num_doc+'/'+$scope.cla_doc+'/'+$scope.moneda).success(function(data1){
							$scope.dds = data1;


							// genera txt det
							if($scope.det=='S'){
								var deta = '';
								var i=0;
								data1.forEach(function(){
									data1[i].forEach(function(entry){
										deta += entry+'|';										
									});
									deta += '\r\n';
									i++;




								});
									
								var detalle = new File([deta], { type: 'text/plain;charset=utf-8' });
								FileSaver.saveAs(detalle, data[0][20]); 
							}
						});
					}else if ($scope.tip_imp=='R'){ // impresion con resumen
						if($scope.det=='S'){
							var deta = 'NIU|1.00|||'+ data[0][19] +'|0.00|0.00|0.00|30|0.00|02|0.00|0.00|';
							var detalle = new File([deta], { type: 'text/plain;charset=utf-8' });
							FileSaver.saveAs(detalle, data[0][20]);
						}
						
					}
				}else if($scope.co_cr_an=='AN'){ // si es anticipo tiene un detalle
					if($scope.det=='S'){
						var deta = 'NIU|1.00|||'+ data[0][18] +'|0.00|0.00|0.00|30|0.00|02|0.00|0.00|';
						var detalle = new File([deta], { type: 'text/plain;charset=utf-8' });
						FileSaver.saveAs(detalle, data[0][20]);
					}
				}
			});


				
				
			
			
		// Nota de Credito		
		}else if($scope.cla_doc=='AR' || $scope.cla_doc=='AS') { 


			// cabezera
			$http.get('/apis/ncc/'+$scope.gen+'/'+$scope.emp+'/'+$scope.num_doc).success(function(data){
				$scope.fb_cab = data[0];


				// genera cabezera txt
				if($scope.cab=='S'){
					var i=0;
					var cabe = '';
					data[0].forEach(function(entry) {
						if(i<=16){
							cabe += entry+'|';							
						}
	    				i++;


					});	
					var cabezera = new File([cabe], { type: 'text/plain;charset=utf-8' });
					FileSaver.saveAs(cabezera, data[0][17]);	
				}


				// detalle
				if($scope.co_cr_an=='CO' || $scope.co_cr_an=='CR'){
					if ($scope.tip_imp=='D'){
						$http.get( '/apis/dds/'+$scope.gen+'/'+$scope.emp+'/'+$scope.num_doc+'/'+$scope.cla_doc+'/'+$scope.moneda).success(function(data1){
							$scope.dds = data1;
							// genera txt det
							if($scope.det=='S'){
								var deta = '';
								var i=0;
								data1.forEach(function(){
									data1[i].forEach(function(entry){
										deta += entry+'|';										
									});
									deta += '\r\n';
									i++;
								});
									
								var detalle = new File([deta], { type: 'text/plain;charset=utf-8' });
								FileSaver.saveAs(detalle, data[0][20]);
							}
						});
					}else if ($scope.tip_imp=='R'){ // impresion con resumen
						if($scope.det=='S'){
							var deta = 'NIU|1.00|||'+ data[0][19] +'|0.00|0.00|0.00|30|0.00|02|0.00|0.00|';
							var detalle = new File([deta], { type: 'text/plain;charset=utf-8' });
							FileSaver.saveAs(detalle, data[0][20]);
						}
					}				
				}else if ($scope.co_cr_an=='AN'){
					if($scope.det=='S'){
						var deta = 'NIU|1.00|||'+ data[0][18] +'|0.00|0.00|0.00|30|0|02|0|0|';
						var detalle = new File([deta], { type: 'text/plain;charset=utf-8' });
						FileSaver.saveAs(detalle, data[0][20]);
					}
				} 


			});			
			
		}		
	};


	$scope.baja = function(gen,emp,num_doc,cla_doc){
		//$scope.baja =  return $http.get('/apis/baja/'+gen+'/'+emp+'/'+num_doc+'/'+cla_doc+'/');		


		$scope.bajada2 = $http.get('/apis/baja/'+gen+'/'+emp+'/'+num_doc+'/'+cla_doc+'/').
	    then(function successCallback(datos){	      
	      return $scope.bajada2=datos;
	    });   


		console.log($scope.bajada2);
		
	};


	$scope.genpdf = function(doc){
		var doc = new jsPDF();		
		doc.text('PDF en construccion Surmotriz', 10, 10)
		doc.save('documento_exportado.pdf')
	};