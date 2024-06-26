import React from 'react';
import {Helper} from '../helper';
import {useDebounce} from '../castomHooks/useDebounce';
import {useQuatityViewer} from '../castomHooks/useQuatityViewer';


export const CatalogItem = ({prod, sendProductToBasket, setLastTooltip}) => {

    const [quantity, setQuantity] = React.useState(0);
    const quantityView = useQuatityViewer(prod.quantityInBasket, quantity);
    const sendQuantity = useDebounce(quantity, 600, prod.ratio, ((prod.b_catalog_productAVAILABLE === "Y" && Number(prod.b_catalog_productQUANTITY) === 0) || prod.b_catalog_productQUANTITY_TRACE === 'N') ? Infinity : prod.b_catalog_productQUANTITY);
    const [sendQuery, setSendQuery] = React.useState(false);
    const productName = React.useRef(null);

    React.useEffect(() => {

        if (sendQuery && !isNaN(Number(sendQuantity))) {
           sendProductToBasket(
                prod.b_catalog_productID,
                sendQuantity,
                prod.IN_BASKET,
                prod.idInBasket,
            );
        }
    }, [sendQuantity]);

    React.useEffect(() => {
        if (quantity !== prod.quantityInBasket) {
            if (prod.quantityInBasket !== undefined) {
                setQuantity(prod.quantityInBasket);
            }
        }
    }, [prod.quantityInBasket])

    return <li
        key={prod.b_catalog_productID}
        className="catalog-list__item"
        onMouseMove={() => setSendQuery(true)}>

    <div className="catalog-list__column catalog-list__column__size-10"
    >
        <a className="img-responsive-wriper" href="#"><img
            className="img-responsive"
            src={prod.PREVIEW_PICTURE || '/local/templates/b2bcabinet/assets/images/no_photo.svg'}
            width="45"
            height="auto"
        /></a>
    </div>
    <div className="catalog-list__column catalog-list__column__size-all">
        <div className="catalog-list__description">
            <span className="catalog-list__name"
                  title={prod.NAME}
                ref={productName}
                onClick={() => {
                    const http = window.location.protocol;
                    const host = window.location.host;
                    window.open(`${http}//${host}${prod.DETAIL_PAGE_URL}`)
                }}>
                {prod.NAME}
            </span>
            <span className="catalog-list__property">
                {Array.isArray(prod.LIST_PAGE_SHOW)
                    ? prod.LIST_PAGE_SHOW.map(i => <span key={i.NAME + i.VALUE}>{`${i.NAME}: ${i.VALUE}`}</span>)
                    : null}
            </span>
            <span className="catalog-list__property">
                {Array.isArray(prod.OFFER_TREE)
                    ? prod.OFFER_TREE.map(i => <span key={i.NAME + i.VALUE}>{i.NAME}: {i.TYPE === 'img'
                        ? <img src={i.VALUE} alt={i.VALUE} />
                        : <span>{i.VALUE} </span>}</span>)
                    : null}
            </span>
        </div>
    </div>
        <div className="catalog-list__column catalog-list__column__size-22">
            <div className="input-group-basket">
                <span className="input-group-prepend"
                    onClick={() => setQuantity(() =>
                        Helper.calcQuantity(quantityView, ((prod.b_catalog_productAVAILABLE === "Y" && Number(prod.b_catalog_productQUANTITY) === 0) || prod.b_catalog_productQUANTITY_TRACE === 'N') ? Infinity : prod.b_catalog_productQUANTITY, -prod.ratio)
                    )}>
                        -
                </span>
                <input
                    type="text"
                    className="form-control"
                    value={quantityView ?? 0}
                    onChange={e => {
                        let tmpQuantity = e.target.value === '' ? 0 : Number(e.target.value);

                        if (tmpQuantity > prod.b_catalog_productQUANTITY) {
                            tmpQuantity = prod.b_catalog_productQUANTITY;
                        }

                        if (tmpQuantity <= prod.b_catalog_productQUANTITY && !isNaN(tmpQuantity)) {
                            setQuantity(tmpQuantity);
                        }
                    }
                }
                />
                <span className="input-group-append"
                    onClick={() => setQuantity(() =>
                        Helper.calcQuantity(quantityView,
                            ((prod.b_catalog_productAVAILABLE === "Y" && Number(prod.b_catalog_productQUANTITY) === 0) || prod.b_catalog_productQUANTITY_TRACE === 'N') ? Infinity : prod.b_catalog_productQUANTITY,
                            prod.ratio
                        ),
                    )}>
                        +
                </span>
            </div>
        </div>
        <div
            className="catalog-list__column catalog-list__column__size-18 catalog-list__font-white-space-nowrap">
            <div dangerouslySetInnerHTML={{ __html: prod.DISPLAY_PRICE }}></div>
            {prod.DISPLAY_PRICE_WHITHOUT_DISCOND
                ? <div className='price_whithout_discond'
                        dangerouslySetInnerHTML={{ __html: prod.DISPLAY_PRICE_WHITHOUT_DISCOND }}>
                    </div>
                : null}
        </div>
</li>
}