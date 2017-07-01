create or replace package PKG_ELECTRONICA as
  procedure docs(gen VARCHAR2,emp VARCHAR2, pag NUMBER,fecha1 VARCHAR2, fecha2 VARCHAR2, docs out sys_refcursor);
  procedure fbc(gen VARCHAR2,emp VARCHAR2, num_doc NUMBER, cla_doc VARCHAR2,fbc out sys_refcursor);
  procedure adfbc(gen VARCHAR2,emp VARCHAR2, num_doc NUMBER, cla_doc VARCHAR2, adfbc out sys_refcursor);
  procedure ncc(gen VARCHAR2,emp VARCHAR2, num_doc NUMBER, ncc out sys_refcursor);
  procedure dds(gen VARCHAR2,emp VARCHAR2, num_doc number, cla_doc VARCHAR2, moneda VARCHAR2, dds out sys_refcursor);
  procedure baja(gen VARCHAR2,emp VARCHAR2, num_doc number, cla_doc VARCHAR2, baja out sys_refcursor);
end PKG_ELECTRONICA;


create or replace package body PKG_ELECTRONICA as
  procedure docs(gen VARCHAR2,emp VARCHAR2,pag NUMBER,fecha1 VARCHAR2, fecha2 VARCHAR2, docs out sys_refcursor) is
  p_inicial  NUMBER := 1 + 50*(pag-1);
  p_final NUMBER := 50*(pag);
  begin
    if fecha1 = 'N' and fecha2 = 'N' then
      open docs for
        select * from(select ROWNUM rn, a.* from(select
        cdg_num_doc as num_doc0,-- nombre2
        to_char(CDG_FEC_GEN,'dd-mm-yyyy') as fec_gen1, -- 1
        CDG_NOM_CLI as nom_cli2, -- 2
        CDG_CLA_DOC as cla_doc3, -- 3
        CDG_CO_CR as co_cr_an4, -- 4
        CDG_EXI_FRA as fq5, -- 5
        CDG_TIP_IMP as tip_imp6,  -- 6
        cdg_imp_neto as vvp_tot7, -- 7
        decode(cdg_tip_cam,0,'PEN','USD')  as soles8, -- 8
        CDG_TIPO_FACTURA as tipo_factura9, -- 9
        decode(cdg_tip_doc,'F','01','B','03','A','07','0') || '-' || decode(cdg_tip_doc,'F',decode(cdg_ser_doc,1,'F001',2,'F002',3,'F003',4,'F004','F000'),'B',decode(cdg_ser_doc,1,'B001',2,'B002',3,'B003',4,'B004','B000'),'A',decode(cdg_tip_ref,'FS',decode(cdg_ser_doc,1,'FN01',2,'FN02',3,'FN03',4,'FN04','XXXX'),'FR',decode(cdg_ser_doc,1,'FN01',2,'FN02',3,'FN03',4,'FN04','XXXX'),'BS',decode(cdg_ser_doc,1,'BN01',2,'BN02',3,'BN03',4,'BN04','XXXX'),'BR',decode(cdg_ser_doc,1,'BN01',2,'BN02',3,'BN03',4,'BN04','XXXX'),'XXXX'),'MMMM') || '-'|| cdg_num_doc as nombre10, -- 0
        cdg_anu_sn as anu_sn11,
        cdg_doc_anu as doc_anu12,
        cdg_tip_doc,
        cdg_tip_ref,
        cdg_cod_emp,
        cdg_exi_fra,
        cdg_exi_ant
        from cab_doc_gen where to_char(cdg_fec_gen,'dd-mm-yyyy') = to_char(sysdate,'dd-mm-yyyy') and cdg_cod_gen=gen and cdg_cod_emp=emp order by cdg_tip_doc, cdg_num_doc Desc) a where  ROWNUM<=p_final)where rn>=p_inicial;
    elsif fecha1 != 'N' and fecha2 != 'N' then
      open docs for
        select * from(select ROWNUM rn, a.* from(select
        cdg_num_doc as num_doc0, -- 10 nombre2
        CDG_FEC_GEN as fec_gen1, -- 1
        CDG_NOM_CLI as nom_cli2, -- 2
        CDG_CLA_DOC as cla_doc3, -- 3
        CDG_CO_CR as co_cr_an4, -- 4
        CDG_EXI_FRA as fq5, -- 5
        CDG_TIP_IMP as tip_imp6,  -- 6
        cdg_imp_neto as vvp_tot7, -- 7
        decode(cdg_tip_cam,0,'PEN','USD')  as soles8, -- 8
        CDG_TIPO_FACTURA as tipo_factura9, -- 9
        decode(cdg_tip_doc,'F','01','B','03','A','07','0') || '-' || decode(cdg_tip_doc,'F',decode(cdg_ser_doc,1,'F001',2,'F002',3,'F003',4,'F004','F000'),'B',decode(cdg_ser_doc,1,'B001',2,'B002',3,'B003',4,'B004','B000'),'A',decode(cdg_tip_ref,'FS',decode(cdg_ser_doc,1,'FN01',2,'FN02',3,'FN03',4,'FN04','XXXX'),'FR',decode(cdg_ser_doc,1,'FN01',2,'FN02',3,'FN03',4,'FN04','XXXX'),'BS',decode(cdg_ser_doc,1,'BN01',2,'BN02',3,'BN03',4,'BN04','XXXX'),'BR',decode(cdg_ser_doc,1,'BN01',2,'BN02',3,'BN03',4,'BN04','XXXX'),'XXXX'),'MMMM') || '-'|| lpad(cdg_num_doc,8,0) as nombre10, -- 0
        cdg_anu_sn as anu_sn11,
        cdg_doc_anu as doc_anu12,
        cdg_tip_doc,
        cdg_tip_ref,
        cdg_cod_emp,
        cdg_exi_fra,
        cdg_exi_ant
        from cab_doc_gen where cdg_cod_gen=gen and cdg_cod_emp=emp and cdg_fec_gen >= to_date(fecha1,'dd-mm-yyyy') and  cdg_fec_gen < (to_date(fecha2,'dd-mm-yyyy')+1) order by cdg_tip_doc Asc) a where  ROWNUM<=p_final)where rn>=p_inicial;
    end if;

  end;
  procedure fbc(gen VARCHAR2,emp VARCHAR2,num_doc NUMBER, cla_doc VARCHAR2, fbc out sys_refcursor) is
  begin
    open fbc for
      select
        lpad('1',2,0) as tipOperacion0,
        to_char(cdg_fec_gen, 'YYYY-mm-dd') as fecEmision1,
        '000' as codLocalEmisor2,
        decode((select c_c_tipo_documento from cliente where cod_gen=cdg_cod_gen and rtrim(c_c_codigo)=rtrim(cdg_cod_cli)),'01','6','02','1','4') as tipDocUsuario3,
        TRIM(cdg_doc_cli) as numDocUsuario4,
        cdg_nom_cli as rznSocialUsuario5,
        decode(cdg_tip_cam,0,'PEN','USD') as tipMoneda6,
        '0.00' as sumDsctoGlobal7,
        '0.00' as sumOtrosCargos8,
        to_char(round(decode(cdg_tip_cam,0,cdg_des_tot,cdg_des_dol),2),'FM99990.00') as mtoDescuentos9, -- Descuentos
        to_char(round(decode(cdg_exi_fra,'S',((cdg_vvp_tot)-(cdg_tot_fra/(1+cdg_por_igv/100))),decode(cdg_tip_cam,0,cdg_vvp_tot-cdg_des_tot,cdg_vvp_dol-cdg_des_dol)),2),'FM99990.00') as mtoOperGravadas10,
        '0.00' as mtoOperInafectas11,
        '0.00' as mtoOperExoneradas12,
        to_char(round(decode(cdg_exi_fra,'S',(cdg_igv_tot -(cdg_tot_fra/(1+cdg_por_igv/100))*(cdg_por_igv/100)),decode(cdg_tip_cam,0,cdg_igv_tot,cdg_igv_dol)),2),'FM99990.00') as mtoIGV13, -- IGV
        to_char(round(decode(cdg_exi_fra,'S',((cdg_vvp_tot)-(cdg_tot_fra/(1+cdg_por_igv/100))),decode(cdg_tip_cam,0,cdg_vvp_tot,cdg_vvp_dol-cdg_des_dol)),2),'FM99990.00') as subtotal14,
        '0.00' as mtoOtrosTributos15,
        to_char(round(cdg_imp_neto,2),'FM99990.00') as mtoImpVenta16, -- Total
        '20532710066' || '-' || decode(cdg_tip_doc,'F','01','B','03','A','07','0') || '-' || decode(cdg_tip_doc,'F',decode(cdg_ser_doc,1,'F001',2,'F002',3,'F003',4,'F004','F000'),'B',decode(cdg_ser_doc,1,'B001',2,'B002',3,'B003',4,'B004','B000'),'MMMM') || '-'|| cdg_num_doc || '.xml' as nombre17,
        cdg_not_001 as cdg_not_001_18,
        cdg_ten_res as cdg_ten_res19,
        '20532710066' || '-' || decode(cdg_tip_doc,'F','01','B','03','A','07','0') || '-' || decode(cdg_tip_doc,'F',decode(cdg_ser_doc,1,'F001',2,'F002',3,'F003',4,'F004','F000'),'B',decode(cdg_ser_doc,1,'B001',2,'B002',3,'B003',4,'B004','B000'),'MMMM') || '-'|| cdg_num_doc || '.zip' as nombre20,
        cdg_anu_sn as anu_sn21,
        cdg_doc_anu as doc_anu22,
        decode(cdg_co_cr,'CO','Contado','CR','Credito','AN','Anticipo','No Definido') as doc23,
        cdg_dir_cli as DOC24,
        decode(cdg_tip_doc,'F','01','B','03','A','07','0') as tipodoc,
        decode(cdg_tip_doc,'F',decode(cdg_ser_doc,1,'F001',2,'F002',3,'F003',4,'F004','F000'),'B',decode(cdg_ser_doc,1,'B001',2,'B002',3,'B003',4,'B004','B000'),'MMMM') as serie,
        lpad(cdg_num_doc,8,0) as documento,
        cdg_tip_doc,
        cdg_num_doc,
        decode(cdg_tip_doc,'F','01','B','03','A','07','0') as cdg_tipo,
        decode(cdg_tip_doc,'F',decode(cdg_ser_doc,1,'F001',2,'F002',3,'F003',4,'F004','F000'),'B',decode(cdg_ser_doc,1,'B001',2,'B002',3,'B003',4,'B004','B000'),'MMMM') as serie_doc,
        cdg_exi_fra,
        cdg_exi_ant,
        CDG_TOT_FRA,
        CDG_SER_FRA,
        CDG_DOC_FRA,
        CDG_TIP_FRA
      from cab_doc_gen where cdg_cod_gen=gen and cdg_cod_emp=emp and cdg_num_doc=num_doc and cdg_cla_doc=cla_doc;
  end;

  procedure adfbc(gen VARCHAR2,emp VARCHAR2,num_doc NUMBER, cla_doc VARCHAR2, adfbc out sys_refcursor) is
  begin
    open adfbc for
      select
        '' as codRegPercepcion0,
        '' as mtoBaseImponiblePercepcion1,
        '' as mtoPercepcion2,
        '' as mtoTotalIncPercepcion3,
        '' as mtoOperGratuitas4,
        '' as mtoTotalAnticipo5,
        '' as codPaisCliente6,
        '' as codUbigeoCliente7,
        cdg_dir_cli as desDireccionCliente8,
        '' as codPaisEntrega9,
        '' as codUbigeoEntrega10,
        '' as desDireccionEntrega11,
        to_char(cdg_fec_ven,'yyyy-mm-dd') as fecVencimiento12
      from cab_doc_gen where cdg_cod_gen=gen and cdg_cod_emp=emp and cdg_cla_doc=cla_doc and cdg_num_doc=num_doc;
  end;

  procedure ncc(gen VARCHAR2,emp VARCHAR2, num_doc NUMBER,ncc out sys_refcursor) is
  begin
    open ncc for
      select
        to_char(cdg_fec_gen, 'YYYY-mm-dd') as fecEmision1,
        '03' as codMotivo1,
        cdg_not_001 as desMotivo2,
        decode(cdg_tip_ref,'FS','01','FR','01','FC','01','BR','03','BS','03','00') as tipDocAfectado3,
        (select decode(cdg_tip_doc,'F',decode(cdg_ser_doc,1,'F001',2,'F002',3,'F003',4,'F004','F000'),'B',decode(cdg_ser_doc,1,'B001',2,'B002',3,'B003',4,'B004','B000'),'MMMM') ||'-'||lpad(cdg_num_doc,8,0) from cab_doc_gen a where a.cdg_cla_doc=b.cdg_tip_ref and a.cdg_num_doc=b.cdg_doc_ref) as numDocUsuario4,
        decode((select c_c_tipo_documento from cliente where cod_gen=cdg_cod_gen and rtrim(c_c_codigo)=rtrim(cdg_cod_cli)),'01','6','02','1','4') as tipDocUsuario3,
        replace(cdg_doc_cli,'-','') as numDocUsuario4,
        cdg_nom_cli as rznSocialUsuario5,
        decode(cdg_tip_cam,0,'PEN','USD') as tipMoneda6,
        to_char(cdg_des_tot,'FM99990.00') as MTODESCUENTOS9,
        to_char(round(decode(cdg_exi_fra,'S',((cdg_vvp_tot-cdg_des_tot)-(cdg_tot_fra/(1+cdg_por_igv/100))),decode(cdg_tip_cam,0,cdg_vvp_tot-cdg_des_tot,cdg_vvp_dol-cdg_des_dol)),2),'FM99990.00') as mtoOperGravadas10,
        '0.00' as mtoOperInafectas11,
        '0.00' as mtoOperExoneradas12,
        decode(cdg_exi_fra,'S',(cdg_igv_tot -(cdg_tot_fra/(1+cdg_por_igv/100))*(cdg_por_igv/100)),decode(cdg_tip_cam,0,cdg_igv_tot,cdg_igv_dol)) as mtoIGV13,
        --decode(cdg_tip_cam,0,cdg_igv_tot,cdg_igv_dol) as mtoIGV13,
        '0.00' as mtoISC14,
        '0.00' as mtoOtrosTributos15,
        cdg_imp_neto as mtoImpVenta16,
        '20532710066' || '-' || decode(cdg_tip_doc,'F','01','B','03','A','07','0') || '-' || decode(cdg_tip_ref,'FS',decode(cdg_ser_doc,1,'FN01',2,'FN02',3,'FN03',4,'FN04','XXXX'),'FR',decode(cdg_ser_doc,1,'FN01',2,'FN02',3,'FN03',4,'FN04','XXXX'),'BS',decode(cdg_ser_doc,1,'BN01',2,'BN02',3,'BN03',4,'BN04','XXXX'),'BR',decode(cdg_ser_doc,1,'BN01',2,'BN02',3,'BN03',4,'BN04','XXXX'),'XXXX') || '-'|| cdg_num_doc || '.not' as nombre17,
        cdg_not_001 as cdg_not_001_18,
        cdg_ten_res as cdg_ten_res19,
        '20532710066' || '-' || decode(cdg_tip_doc,'F','01','B','03','A','07','0') || '-' || decode(cdg_tip_ref,'FS',decode(cdg_ser_doc,1,'FN01',2,'FN02',3,'FN03',4,'FN04','XXXX'),'FR',decode(cdg_ser_doc,1,'FN01',2,'FN02',3,'FN03',4,'FN04','XXXX'),'BS',decode(cdg_ser_doc,1,'BN01',2,'BN02',3,'BN03',4,'BN04','XXXX'),'BR',decode(cdg_ser_doc,1,'BN01',2,'BN02',3,'BN03',4,'BN04','XXXX'),'XXXX') || '-'|| cdg_num_doc || '.det' as nombre20,
        cdg_anu_sn as anu_sn21,
        cdg_doc_anu as doc_anu22,
        decode(cdg_co_cr,'CO','Contado','CR','Credito','AN','Anticipo','No Definido') as doc23,
        CDG_DIR_CLI as doc24,
        to_char(round(decode(cdg_exi_fra,'S',((cdg_vvp_tot-cdg_des_tot)-(cdg_tot_fra/(1+cdg_por_igv/100))),decode(cdg_tip_cam,0,cdg_vvp_tot,cdg_vvp_dol)),2),'FM99990.00') as SUBTOTAL14,
        cdg_doc_ref as doc_ref,
        cdg_tip_ref as tip_ref,
        decode(cdg_tip_ref,'FS','F00','FR','F00','BS','B00','BR','B00','FC','F00','') || (select cdg_ser_doc from cab_doc_gen where cdg_num_doc=b.cdg_doc_ref and cdg_cla_doc=b.cdg_tip_ref and cdg_cod_gen=b.cdg_cod_gen and cdg_cod_emp=b.cdg_cod_emp) || '-' || lpad(cdg_doc_ref,8,0)  as serie,
        decode(cdg_tip_ref,'FS','01','FR','01','FC','01','BS','03','BR','03','') as tiporef,
        cdg_tip_doc,
        cdg_num_doc,
        decode(cdg_tip_doc,'F','01','B','03','A','07','0') as cdg_tipo,
        decode(cdg_tip_ref,'FS',decode(cdg_ser_doc,1,'FN01',2,'FN02',3,'FN03',4,'FN04','XXXX'),'FR',decode(cdg_ser_doc,1,'FN01',2,'FN02',3,'FN03',4,'FN04','XXXX'),'BS',decode(cdg_ser_doc,1,'BN01',2,'BN02',3,'BN03',4,'BN04','XXXX'),'BR',decode(cdg_ser_doc,1,'BN01',2,'BN02',3,'BN03',4,'BN04','XXXX'),'XXXX') as serie_doc
      from cab_doc_gen b where cdg_cod_gen=gen and cdg_cod_emp=emp and cdg_tip_doc='A' and cdg_num_doc=num_doc;
  end;
  procedure dds(gen VARCHAR2,emp VARCHAR2, num_doc number, cla_doc VARCHAR2, moneda VARCHAR2, dds out sys_refcursor) is
  begin
    open dds for
      select
        'NIU' as codUnidadMedida0, -- 0
        to_char(round(dds_can_pro,2),'FM99990.00') as ctdUnidadItem1, -- 1
        dds_cod_pro as codProducto2, -- 2
        '0000' as codProductoSUNAT3, -- 3
        dds_des_001 as desItem4, -- 4
        to_char(round((decode(moneda,'PEN',dds_vvp_sol,dds_vvp_dol)),2),'FM99990.00') as mtoValorUnitario5, -- 5
        to_char(round(decode(moneda,'PEN',((dds_can_pro*dds_vvp_sol)*(dds_por_des/100)), ((dds_can_pro*dds_vvp_dol)*(dds_por_des/100))),2),'FM99990.00') as mtoDsctoItem6, -- 6
        to_char(round(decode(moneda,'PEN',(((dds_can_pro*dds_vvp_sol)-((dds_can_pro*dds_vvp_sol)*(dds_por_des/100)))*0.18), (((dds_can_pro*dds_vvp_dol)-((dds_can_pro*dds_vvp_dol)*(dds_por_des/100)))*0.18)),2),'FM99990.00') as mtoIgvItem7, -- 7
        '10' as tipAfeIGV8, -- 8
        '0.00' as mtoIscItem9, -- 9
        '02' as tipSisISC10, -- 10
        to_char(round(decode(moneda,'PEN',(dds_can_pro*dds_vvp_sol),(dds_can_pro*dds_vvp_dol)),2),'FM99990.00') as mtoPrecioVentaItem11, -- 11 importe
        to_char(round(decode(moneda,'PEN',((dds_can_pro*dds_vvp_sol)-((dds_can_pro*dds_vvp_sol)*(dds_por_des/100))), ((dds_can_pro*dds_vvp_dol)-((dds_can_pro*dds_vvp_dol)*(dds_por_des/100)))),2),'FM99990.00') as mtoValorVentaItem12 -- 12
      from DET_DOC_SER where DDS_COD_GEN=gen and DDS_COD_EMP=emp and DDS_NUM_DOC=num_doc and DDS_CLA_DOC=cla_doc
      union
      select
        'NIU' as codUnidadMedida0, -- 0
        to_char(round(ddr_can_pro,2),'FM99990.00') as ctdUnidadItem1, -- 1
        ddr_cod_pro as codProducto2, -- 2
        '0000' as codProductoSUNAT3, -- 3
        (select lpr_des_pro from LIS_PRE_REP where lpr_cod_gen=ddr_cod_gen and lpr_cod_pro=ddr_cod_pro) as desItem4, -- 4
        to_char(decode(moneda,'PEN',ddr_vvp_sol, ddr_vvp_dol),'FM99990.00') as mtoValorUnitario5, -- 5
        to_char(decode(moneda,'PEN',((ddr_can_pro*ddr_vvp_sol)*(ddr_por_des/100)),((ddr_can_pro*ddr_vvp_dol)*(ddr_por_des/100))),'FM99990.00') as mtoDsctoItem6, --6
        to_char(decode(moneda,'PEN',(((ddr_can_pro*ddr_vvp_sol)-((ddr_can_pro*ddr_vvp_sol)*(ddr_por_des/100)))*0.18),(((ddr_can_pro*ddr_vvp_dol)-((ddr_can_pro*ddr_vvp_dol)*(ddr_por_des/100)))*0.18)),'FM99990.00') as mtoIgvItem7, -- 7
        '10' as tipAfeIGV8, -- 8
        '0.00' as mtoIscItem9, -- 9
        '02' as tipSisISC10, -- 10
        to_char(decode(moneda,'PEN',(ddr_can_pro*ddr_vvp_sol),(ddr_can_pro*ddr_vvp_dol)),'FM99990.00')  as mtoPrecioVentaItem11, -- 11
        to_char(decode(moneda,'PEN',((ddr_can_pro*ddr_vvp_sol)-((ddr_can_pro*ddr_vvp_sol)*(ddr_por_des/100))), ((ddr_can_pro*ddr_vvp_dol)-((ddr_can_pro*ddr_vvp_dol)*(ddr_por_des/100)))),'FM99990.00') as mtoValorVentaItem12 --12
      from DET_DOC_REP where DDR_COD_GEN=gen and DDR_COD_EMP=emp and DDR_NUM_DOC=num_doc and DDR_CLA_DOC=cla_doc
      union
      select
        'NIU' as codUnidadMedida0, -- 0
        to_char(round(0,2),'FM99990.00') as ctdUnidadItem1, -- 1
        '0' as codProducto2, -- 2
        '0' as codProductoSUNAT3, -- 3
        ddo_des_otr as desItem4, -- 4
        '0.00' as mtoValorUnitario5, -- 5
        '0.00' as mtoDsctoItem6, -- 6
        '0.00' as mtoIgvItem7, -- 7
        '30' as tipAfeIGV8, -- 8
        '0.00' as mtoIscItem9, -- 9
        '02' as tipSisISC10, -- 10
        '0.00' as mtoPrecioVentaItem11, -- 11 importe
        '0.00' as mtoValorVentaItem12 -- 12
      from DET_DOC_OTR where DDO_COD_GEN=gen and DDO_COD_EMP=emp and DDO_NUM_DOC=num_doc and DDO_CLA_DOC=cla_doc;
  end;
  procedure baja(gen VARCHAR2,emp VARCHAR2, num_doc number, cla_doc VARCHAR2, baja out sys_refcursor) is
  begin
    open baja for
        select
          to_char(cdg_fec_gen,'yyyy-mm-dd') as fec_gen1,
          to_char(sysdate,'yyyy-mm-dd') as fec_com2,
          decode(cdg_tip_doc,'F','01','B','03','07') as tip_doc_baja3,
          decode(cdg_tip_doc,'F',decode(cdg_ser_doc,1,'F001',2,'F002',3,'F003',4,'F004','F000'),'B',decode(cdg_ser_doc,1,'B001',2,'B002',3,'B003',4,'B004','B000'),'MMMM') ||'-'||lpad(cdg_num_doc,8,0) as num_doc_baja4,
          'Descripcion detalle baja' as des_motivo_baja5,
          '20532710066-' || 'RA-' || to_char(sysdate,'yyyymmdd') || '-' || SUBSTR(cdg_num_doc,3) || '.CBA' as nombre6
        from cab_doc_gen where cdg_cod_gen=gen and cdg_cod_emp=emp and cdg_num_doc=num_doc and cdg_cla_doc=cla_doc;
  end;
end PKG_ELECTRONICA;
