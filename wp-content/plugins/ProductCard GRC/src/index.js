import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { PanelBody, TextControl, SelectControl, Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import './editor.scss';

registerBlockType('product-card/card', {
    edit: ({ attributes, setAttributes }) => {
        const {
            status,
            productName,
            imageUrl,
            userExperience,
            observation,
            verdict,
            bestFor,
            rating,
            certification,
            moneyBackGuarantee,
            price,
            originalPrice,
            currency,
            ctaLabel,
            ctaSubText = 'from Official Website',
            ctaUrl
        } = attributes;

        const statusClass = status === 'scam' ? 'pcp-status-scam' : 'pcp-status-legit';
        const statusLabel = status.charAt(0).toUpperCase() + status.slice(1);

        return (
            <>
                <InspectorControls>
                    <PanelBody title={__('Product Settings', 'product-card-plugin')} initialOpen={true}>
                        <SelectControl
                            label={__('Status', 'product-card-plugin')}
                            value={status}
                            options={[
                                { label: 'Legit', value: 'legit' },
                                { label: 'Scam', value: 'scam' }
                            ]}
                            onChange={(value) => setAttributes({ status: value })}
                        />
                        
                        <TextControl
                            label={__('Product Name', 'product-card-plugin')}
                            value={productName}
                            onChange={(value) => setAttributes({ productName: value })}
                        />

                        <TextControl
                            label={__('Expert Rating (0-10)', 'product-card-plugin')}
                            value={rating}
                            onChange={(value) => setAttributes({ rating: value })}
                            type="number"
                            min="0"
                            max="10"
                            step="0.1"
                        />
                    </PanelBody>

                    <PanelBody title={__('Product Image', 'product-card-plugin')} initialOpen={false}>
                        <MediaUploadCheck>
                            <MediaUpload
                                onSelect={(media) => setAttributes({ imageUrl: media.url })}
                                allowedTypes={['image']}
                                value={imageUrl}
                                render={({ open }) => (
                                    <div>
                                        {imageUrl && (
                                            <img 
                                                src={imageUrl} 
                                                alt="Product" 
                                                style={{ maxWidth: '100%', marginBottom: '10px' }}
                                            />
                                        )}
                                        <Button onClick={open} variant="secondary">
                                            {imageUrl ? __('Change Image', 'product-card-plugin') : __('Select Image', 'product-card-plugin')}
                                        </Button>
                                    </div>
                                )}
                            />
                        </MediaUploadCheck>
                        
                        <TextControl
                            label={__('Or Image URL', 'product-card-plugin')}
                            value={imageUrl}
                            onChange={(value) => setAttributes({ imageUrl: value })}
                            help={__('Enter image URL directly', 'product-card-plugin')}
                        />
                    </PanelBody>

                    <PanelBody title={__('Content', 'product-card-plugin')} initialOpen={false}>
                        <TextControl
                            label={__('User Experience', 'product-card-plugin')}
                            value={userExperience}
                            onChange={(value) => setAttributes({ userExperience: value })}
                        />

                        <TextControl
                            label={__('Our Observation', 'product-card-plugin')}
                            value={observation}
                            onChange={(value) => setAttributes({ observation: value })}
                        />

                        <TextControl
                            label={__('Verdict', 'product-card-plugin')}
                            value={verdict}
                            onChange={(value) => setAttributes({ verdict: value })}
                        />

                        <TextControl
                            label={__('Best For', 'product-card-plugin')}
                            value={bestFor}
                            onChange={(value) => setAttributes({ bestFor: value })}
                        />
                    </PanelBody>

                    <PanelBody title={__('Credentials', 'product-card-plugin')} initialOpen={false}>
                        <TextControl
                            label={__('Certification', 'product-card-plugin')}
                            value={certification}
                            onChange={(value) => setAttributes({ certification: value })}
                        />

                        <TextControl
                            label={__('Money Back Guarantee', 'product-card-plugin')}
                            value={moneyBackGuarantee}
                            onChange={(value) => setAttributes({ moneyBackGuarantee: value })}
                        />
                    </PanelBody>

                    <PanelBody title={__('Pricing & CTA', 'product-card-plugin')} initialOpen={false}>
                        <TextControl
                            label={__('Currency Symbol', 'product-card-plugin')}
                            value={currency}
                            onChange={(value) => setAttributes({ currency: value })}
                        />

                        <TextControl
                            label={__('Original Price', 'product-card-plugin')}
                            value={originalPrice}
                            onChange={(value) => setAttributes({ originalPrice: value })}
                            type="number"
                        />

                        <TextControl
                            label={__('Current Price', 'product-card-plugin')}
                            value={price}
                            onChange={(value) => setAttributes({ price: value })}
                            type="number"
                        />

                        <TextControl
                            label={__('Buy Now Button Main Text', 'product-card-plugin')}
                            value={ctaLabel}
                            onChange={(value) => setAttributes({ ctaLabel: value })}
                        />

                        <TextControl
                            label={__('Buy Now Button Subtext', 'product-card-plugin')}
                            value={ctaSubText}
                            onChange={(value) => setAttributes({ ctaSubText: value })}
                        />

                        <TextControl
                            label={__('CTA Button URL', 'product-card-plugin')}
                            value={ctaUrl}
                            onChange={(value) => setAttributes({ ctaUrl: value })}
                            type="url"
                        />
                    </PanelBody>
                </InspectorControls>

                <div className="pcp-card-wrapper">
                    <div className="pcp-card">
                        <div className={`pcp-status-badge ${statusClass}`}>
                            {statusLabel}
                        </div>
                        
                        <div className="pcp-card-inner">
                            <div className="pcp-image-column">
                                <div className="pcp-image-container">
                                    {imageUrl ? (
                                        <img src={imageUrl} alt={productName} className="pcp-product-image" />
                                    ) : (
                                        <div className="pcp-image-placeholder">No Image</div>
                                    )}
                                </div>
                            </div>
                            
                            <div className="pcp-content-column">
                                <h2 className="pcp-product-title">{productName}</h2>
                                
                                {userExperience && (
                                    <div className="pcp-section">
                                        <div className="pcp-section-label">User Experience:</div>
                                        <p className="pcp-section-text">{userExperience}</p>
                                    </div>
                                )}

                                {observation && (
                                    <div className="pcp-section">
                                        <div className="pcp-section-label">Our Observation:</div>
                                        <p className="pcp-section-text">{observation}</p>
                                    </div>
                                )}

                                {verdict && (
                                    <div className="pcp-verdict-box">
                                        <strong>Verdict:</strong> {verdict}
                                    </div>
                                )}

                                <div className="pcp-info-grid">
                                    {bestFor && (
                                        <div className="pcp-info-item">
                                            <div className="pcp-info-label">Best for:</div>
                                            <div className="pcp-info-value">{bestFor}</div>
                                        </div>
                                    )}

                                    {rating && (
                                        <div className="pcp-info-item">
                                            <div className="pcp-info-label">Expert Rating:</div>
                                            <div className="pcp-info-value pcp-rating-value">{rating}/10</div>
                                        </div>
                                    )}

                                    {certification && (
                                        <div className="pcp-info-item">
                                            <div className="pcp-info-label">Certification:</div>
                                            <div className="pcp-info-value">{certification}</div>
                                        </div>
                                    )}

                                    {moneyBackGuarantee && (
                                        <div className="pcp-info-item">
                                            <div className="pcp-info-label">Money Back Guarantee:</div>
                                            <div className="pcp-info-value">{moneyBackGuarantee}</div>
                                        </div>
                                    )}
                                </div>
                                
                                <div className="pcp-footer-row">
                                    <div className="pcp-price-section">
                                        {originalPrice && originalPrice !== price && (
                                            <span className="pcp-original-price">
                                                {currency}{originalPrice}
                                            </span>
                                        )}
                                        <span className="pcp-price">
                                            {currency}{price}
                                        </span>
                                    </div>
                                    
                                    <div className="pcp-cta-section">
                                        <a href={ctaUrl} className="pcp-cta-button">
                                            <span className="pcp-cta-main-text">{ctaLabel}</span>
                                            {ctaSubText && <span className="pcp-cta-sub-text">{ctaSubText}</span>}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </>
        );
    },

    save: ({ attributes }) => {
        const {
            status,
            productName,
            imageUrl,
            userExperience,
            observation,
            verdict,
            bestFor,
            rating,
            certification,
            moneyBackGuarantee,
            price,
            originalPrice,
            currency,
            ctaLabel,
            ctaSubText = 'from Official Website',
            ctaUrl
        } = attributes;

        const statusClass = status === 'scam' ? 'pcp-status-scam' : 'pcp-status-legit';
        const statusLabel = status.charAt(0).toUpperCase() + status.slice(1);

        return (
            <div className="pcp-card-wrapper">
                <div className="pcp-card">
                    <div className={`pcp-status-badge ${statusClass}`}>
                        {statusLabel}
                    </div>
                    
                    <div className="pcp-card-inner">
                        <div className="pcp-image-column">
                            <div className="pcp-image-container">
                                {imageUrl ? (
                                    <img src={imageUrl} alt={productName} className="pcp-product-image" />
                                ) : (
                                    <div className="pcp-image-placeholder">No Image</div>
                                )}
                            </div>
                        </div>
                        
                        <div className="pcp-content-column">
                            <h2 className="pcp-product-title">{productName}</h2>
                            
                            {userExperience && (
                                <div className="pcp-section">
                                    <div className="pcp-section-label">User Experience:</div>
                                    <p className="pcp-section-text">{userExperience}</p>
                                </div>
                            )}

                            {observation && (
                                <div className="pcp-section">
                                    <div className="pcp-section-label">Our Observation:</div>
                                    <p className="pcp-section-text">{observation}</p>
                                </div>
                            )}

                            {verdict && (
                                <div className="pcp-verdict-box">
                                    <strong>Verdict:</strong> {verdict}
                                </div>
                            )}

                            <div className="pcp-info-grid">
                                {bestFor && (
                                    <div className="pcp-info-item">
                                        <div className="pcp-info-label">Best for:</div>
                                        <div className="pcp-info-value">{bestFor}</div>
                                    </div>
                                )}

                                {rating && (
                                    <div className="pcp-info-item">
                                        <div className="pcp-info-label">Expert Rating:</div>
                                        <div className="pcp-info-value pcp-rating-value">{rating}/10</div>
                                    </div>
                                )}

                                {certification && (
                                    <div className="pcp-info-item">
                                        <div className="pcp-info-label">Certification:</div>
                                        <div className="pcp-info-value">{certification}</div>
                                    </div>
                                )}

                                {moneyBackGuarantee && (
                                    <div className="pcp-info-item">
                                        <div className="pcp-info-label">Money Back Guarantee:</div>
                                        <div className="pcp-info-value">{moneyBackGuarantee}</div>
                                    </div>
                                )}
                            </div>
                            
                            <div className="pcp-footer-row">
                                <div className="pcp-price-section">
                                    {originalPrice && originalPrice !== price && (
                                        <span className="pcp-original-price">
                                            {currency}{originalPrice}
                                        </span>
                                    )}
                                    <span className="pcp-price">
                                        {currency}{price}
                                    </span>
                                </div>
                                
                                <div className="pcp-cta-section">
                                    <a href={ctaUrl} className="pcp-cta-button" target="_blank" rel="noopener">
                                        <span className="pcp-cta-main-text">{ctaLabel}</span>
                                        {ctaSubText && <span className="pcp-cta-sub-text">{ctaSubText}</span>}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
});
