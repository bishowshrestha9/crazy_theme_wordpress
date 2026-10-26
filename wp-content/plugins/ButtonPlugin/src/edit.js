import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls, InnerBlocks } from '@wordpress/block-editor';
import { PanelBody, TextControl, ColorPalette, SelectControl } from '@wordpress/components';
import { useEffect } from '@wordpress/element';
import './editor.scss';

const LOADING_PRESETS = {
    set1: [
        "Analyzing [Product Name] claims in [Product Category]…",
        "Cross-checking ingredients, dosage, and safety signals…",
        "Filtering out weak formulas and marketing-only products…",
        "Selecting verified alternatives that match your goal…",
        "Almost ready — loading best [Product Category] options now."
    ],
    set2: [
        "Reviewing [Product Name] for red flags…",
        "Matching safer [Product Category] alternatives…",
        "Prioritizing transparency and real-world results…",
        "Loading trusted options…"
    ],
    set3: [
        "Finding better options than [Product Name] in [Product Category]…",
        "Checking what actually works (not what sounds good)…",
        "Shortlisting alternatives with clearer labels and dosages…",
        "Loading your recommended picks…"
    ],
    set4: [
        "Checking [Product Name]…",
        "Comparing top [Product Category] alternatives…",
        "Loading recommendations…"
    ]
};

export default function Edit({ attributes, setAttributes }) {
    const { buttonText, loadingTexts, buttonColor, buttonTextColor, productName, productCategory, loadingPreset } = attributes;

    useEffect(() => {
        const rawTexts = LOADING_PRESETS[loadingPreset] || LOADING_PRESETS.set1;
        const processedTexts = rawTexts.map(text =>
            text.replace(/\[Product Name\]/g, productName || 'this product')
                .replace(/\[Product Category\]/g, productCategory || 'supplements')
        );
        // Only update if different to avoid infinite loops if generic
        if (JSON.stringify(processedTexts) !== JSON.stringify(loadingTexts)) {
            setAttributes({ loadingTexts: processedTexts });
        }
    }, [productName, productCategory, loadingPreset]);

    const onChangeButtonText = (newText) => {
        setAttributes({ buttonText: newText });
    };

    const onChangeButtonColor = (newColor) => {
        setAttributes({ buttonColor: newColor });
    };

    const onChangeButtonTextColor = (newColor) => {
        setAttributes({ buttonTextColor: newColor });
    };

    return (
        <div {...useBlockProps()}>
            <InspectorControls>
                <PanelBody title={__('Button Settings', 'button-plugin')}>
                    <TextControl
                        label={__('Button Text', 'button-plugin')}
                        value={buttonText}
                        onChange={onChangeButtonText}
                    />
                    <p style={{ marginTop: '20px', marginBottom: '10px', fontWeight: '500' }}>{__('Loading Text Configuration', 'button-plugin')}</p>
                    <SelectControl
                        label={__('Loading Script Style', 'button-plugin')}
                        value={loadingPreset}
                        options={[
                            { label: 'Process-Driven (Trust)', value: 'set1' },
                            { label: 'Short & Crisp (Confidence)', value: 'set2' },
                            { label: 'Curated (Reader Friendly)', value: 'set3' },
                            { label: 'Minimal (Simple)', value: 'set4' },
                        ]}
                        onChange={(value) => setAttributes({ loadingPreset: value })}
                    />
                    <TextControl
                        label={__('Product Name', 'button-plugin')}
                        help={__('Replaces [Product Name] in the script.', 'button-plugin')}
                        value={productName}
                        onChange={(value) => setAttributes({ productName: value })}
                    />
                    <TextControl
                        label={__('Product Category', 'button-plugin')}
                        help={__('Replaces [Product Category] in the script.', 'button-plugin')}
                        value={productCategory}
                        onChange={(value) => setAttributes({ productCategory: value })}
                    />

                    <p style={{ marginTop: '20px' }}>{__('Button Appearance', 'button-plugin')}</p>
                    <p>{__('Button Background Color', 'button-plugin')}</p>
                    <ColorPalette
                        value={buttonColor}
                        onChange={onChangeButtonColor}
                    />
                    <p>{__('Button Text Color', 'button-plugin')}</p>
                    <ColorPalette
                        value={buttonTextColor}
                        onChange={onChangeButtonTextColor}
                    />
                </PanelBody>
            </InspectorControls>

            <div className="bp-wp-block-button-plugin-editor">
                <div
                    className="bp-reveal-button"
                    style={{
                        backgroundColor: buttonColor,
                        color: buttonTextColor
                    }}
                >
                    <span>{buttonText || 'Reveal Image'}</span>
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" className="bp-reveal-icon">
                        <path d="M6 9L12 15L18 9" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" />
                    </svg>
                </div>
                <div className="bp-hidden-content-area" style={{ marginTop: '20px', border: '1px dashed #ccc', padding: '10px' }}>
                    <small style={{ display: 'block', marginBottom: '10px', color: '#666' }}>{__('Hidden Content (Drop any block here - Revealed on Click)', 'button-plugin')}</small>
                    <InnerBlocks />
                </div>
            </div>
        </div>
    );
}
