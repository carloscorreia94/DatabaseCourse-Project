/* QUERY 1 */
select distinct pessoa from concorrente where pessoa not in (select pessoa from lance);

/* QUERY 2 */
select nome from pessoa p, concorrente c where nif in(select nif from pessoac) and c.pessoa=p.nif group by pessoa having count(*)=2;

/* QUERY 3 */
select lid from 
 (select v/valorbase as racio,lid from (select leilao,max(valor) as v from lance group by leilao) as x 
    inner join 
        (select valorbase,lid from leilao natural join leilaor) as y on x.leilao=y.lid) 
 as z ORDER BY racio DESC limit 1;


/* QUERY 4 */
select nif from pessoac where capitalsocial in (
select capitalsocial from pessoac group by capitalsocial having count(*) > 1)

/* Trigger */
delimiter //
CREATE TRIGGER verif_bidx BEFORE INSERT ON lance 
for each row
BEGIN
DECLARE v_base int(11);
DECLARE maximum int(11);
select valorbase INTO v_base FROM leilao natural join leilaor where lid=new.leilao;
select max(valor) INTO maximum FROM lance where leilao=new.leilao;
if new.valor < v_base THEN
	CALL ERRO_valor_menor_que_valorbase();
elseif (new.valor <= maximum and new.valor IS NOT NULL) THEN
	CALL ERRO_licitacao_menor_igual_ultimo_lance();
end if;
end; //
delimiter ;

/*INDICES: */
create index capsoc ON pessoac (capitalsocial) USING BTREE;
