import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls, MediaUpload, MediaUploadCheck, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl, TextareaControl, SelectControl, Button, Tooltip } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { useState, useEffect } from '@wordpress/element';
import './editor.scss';
import './style.scss';

const LocalTextControl = ({ label, value, onChange, ...props }) => {
    const [localValue, setLocalValue] = useState(value || '');
    useEffect(() => {
        setLocalValue(value || '');
    }, [value]);
    return (
        <TextControl
            {...props}
            label={label}
            value={localValue}
            onChange={setLocalValue}
            onBlur={() => {
                if (localValue !== value) {
                    onChange(localValue);
                }
            }}
        />
    );
};


registerBlockType('top-choices/list', {
    edit: ({ attributes, setAttributes, clientId }) => {
        const { cards, tagOptions = ["Best Overall", "Expert Recommended", "Highly Recommended", "High Performance"] } = attributes;
        const [newTagName, setNewTagName] = useState('');
        const blockProps = useBlockProps({
            className: "top-choices-editor-form",
            style: { background: '#f8fafc', padding: '20px', border: '1px solid #e2e8f0', borderRadius: '8px' }
        });

        const addTagOption = () => {
            if (!newTagName.trim()) return;
            if (tagOptions.includes(newTagName.trim())) return;
            setAttributes({ tagOptions: [...tagOptions, newTagName.trim()] });
            setNewTagName('');
        };

        const updateTagOption = (tIndex, val) => {
            const updated = [...tagOptions];
            updated[tIndex] = val;
            setAttributes({ tagOptions: updated });
        };

        const deleteTagOption = (tIndex) => {
            const updated = [...tagOptions];
            updated.splice(tIndex, 1);
            setAttributes({ tagOptions: updated });
        };

        // Add a new card to the list
        const addCard = () => {
            const newCards = [...cards];
            newCards.push({
                id: 'card-' + Date.now(),
                tag: 'none',
                productName: 'New Product',
                subTitle: 'Product Subtitle',
                imageUrl: '',
                feature1: 'Feature 1 detail',
                feature2: 'Feature 2 detail',
                feature3: 'Feature 3 detail',
                rating: '4.8/5',
                price: '$89.99',
                btnText: "Read More\nOn Official Website",
                btnUrl: '#'
            });
            setAttributes({ cards: newCards });
        };

        // Remove a card
        const removeCard = (index) => {
            const newCards = [...cards];
            newCards.splice(index, 1);
            setAttributes({ cards: newCards });
        };

        // Move a card up in the list
        const moveUp = (index) => {
            if (index === 0) return;
            const newCards = [...cards];
            const temp = newCards[index];
            newCards[index] = newCards[index - 1];
            newCards[index - 1] = temp;
            setAttributes({ cards: newCards });
        };

        // Move a card down in the list
        const moveDown = (index) => {
            if (index === cards.length - 1) return;
            const newCards = [...cards];
            const temp = newCards[index];
            newCards[index] = newCards[index + 1];
            newCards[index + 1] = temp;
            setAttributes({ cards: newCards });
        };

        // Update attribute values of a single card
        const updateCardField = (index, key, value) => {
            const newCards = [...cards];
            newCards[index] = {
                ...newCards[index],
                [key]: value
            };
            setAttributes({ cards: newCards });
        };

        return (
            <>
                <InspectorControls>
                    <PanelBody title={__('Block Controls', 'top-choices-block')} initialOpen={true}>
                        <Button 
                            variant="primary" 
                            onClick={addCard}
                            style={{ width: '100%', justifyContent: 'center' }}
                        >
                            {__('Add New Product Card', 'top-choices-block')}
                        </Button>
                    </PanelBody>
                    <PanelBody title={__('Manage Tags List', 'top-choices-block')} initialOpen={false}>
                        <div style={{ display: 'flex', gap: '8px', marginBottom: '15px' }}>
                            <TextControl
                                value={newTagName}
                                placeholder="New Tag Name"
                                onChange={(val) => setNewTagName(val)}
                                style={{ margin: 0, flexGrow: 1 }}
                            />
                            <Button variant="secondary" onClick={addTagOption}>
                                {__('Add', 'top-choices-block')}
                            </Button>
                        </div>
                        <ul style={{ listStyle: 'none', padding: 0, margin: 0 }}>
                            {tagOptions.map((tag, tIndex) => (
                                <li key={tIndex} style={{ display: 'flex', alignItems: 'center', gap: '8px', marginBottom: '8px' }}>
                                    <input
                                        value={tag}
                                        onChange={(e) => updateTagOption(tIndex, e.target.value)}
                                        style={{ flexGrow: 1, padding: '4px 8px', fontSize: '13px', border: '1px solid #ccc', borderRadius: '4px' }}
                                    />
                                    <Button isDestructive isSmall onClick={() => deleteTagOption(tIndex)} style={{ padding: '0 8px', minWidth: 'auto' }}>
                                        &times;
                                    </Button>
                                </li>
                            ))}
                        </ul>
                    </PanelBody>
                    <PanelBody title={__('Remove Block', 'top-choices-block')} initialOpen={false}>
                        <Button 
                            isDestructive 
                            variant="primary" 
                            onClick={() => wp.data.dispatch('core/block-editor').removeBlock(clientId)}
                            style={{ width: '100%', justifyContent: 'center' }}
                        >
                            {__('Delete Entire Block', 'top-choices-block')}
                        </Button>
                    </PanelBody>
                </InspectorControls>

                <div { ...blockProps }>
                    <h2 style={{ margin: '0 0 20px 0', fontSize: '18px', fontWeight: '600' }}>{__('Top Choices Block Builder', 'top-choices-block')}</h2>
                    
                    <div style={{ background: '#f1f5f9', padding: '15px', borderRadius: '6px', marginBottom: '20px', border: '1px solid #cbd5e1' }}>
                        <h3 style={{ margin: '0 0 10px 0', fontSize: '14px', fontWeight: '600', color: '#334155' }}>
                            {__('Manage Available Tags', 'top-choices-block')}
                        </h3>
                        <div style={{ display: 'flex', gap: '8px', marginBottom: '12px' }}>
                            <input
                                type="text"
                                value={newTagName}
                                placeholder="Add new tag (e.g. Best Deal)"
                                onChange={(e) => setNewTagName(e.target.value)}
                                style={{ flexGrow: 1, padding: '6px 12px', fontSize: '13px', border: '1px solid #cbd5e1', borderRadius: '4px' }}
                            />
                            <Button variant="secondary" onClick={addTagOption}>
                                {__('Add Tag', 'top-choices-block')}
                            </Button>
                        </div>
                        <div style={{ display: 'flex', flexWrap: 'wrap', gap: '8px' }}>
                            {tagOptions.map((tag, tIndex) => (
                                <div key={tIndex} style={{ display: 'flex', alignItems: 'center', background: '#ffffff', border: '1px solid #cbd5e1', borderRadius: '4px', padding: '4px 8px', gap: '6px' }}>
                                    <input
                                        value={tag}
                                        onChange={(e) => updateTagOption(tIndex, e.target.value)}
                                        style={{ border: 'none', padding: 0, fontSize: '12px', fontWeight: '500', width: '120px', outline: 'none' }}
                                    />
                                    <button 
                                        onClick={() => deleteTagOption(tIndex)} 
                                        style={{ border: 'none', background: 'transparent', cursor: 'pointer', color: '#ef4444', fontSize: '14px', fontWeight: 'bold', padding: '0 2px' }}
                                    >
                                        &times;
                                    </button>
                                </div>
                            ))}
                        </div>
                    </div>

                    {cards.map((card, index) => {
                        const number = index + 1;
                        return (
                            <div key={card.id || index} style={{ 
                                background: '#ffffff', 
                                border: '1px solid #cbd5e1', 
                                borderRadius: '6px', 
                                padding: '20px', 
                                marginBottom: '20px',
                                boxShadow: '0 1px 3px rgba(0,0,0,0.05)',
                                position: 'relative'
                            }}>
                                <div style={{ 
                                    display: 'flex', 
                                    justifyContent: 'space-between', 
                                    alignItems: 'center', 
                                    borderBottom: '1px solid #e2e8f0', 
                                    paddingBottom: '12px', 
                                    marginBottom: '15px' 
                                }}>
                                    <strong style={{ fontSize: '15px', color: '#0f172a' }}>
                                        {`Card #${number}: ${card.productName || 'Unnamed Product'}`}
                                    </strong>
                                    <div style={{ display: 'flex', gap: '8px', marginLeft: 'auto' }}>
                                        <Button isSmall variant="secondary" onClick={() => moveUp(index)} disabled={index === 0}>
                                            {__('Move Up', 'top-choices-block')}
                                        </Button>
                                        <Button isSmall variant="secondary" onClick={() => moveDown(index)} disabled={index === cards.length - 1}>
                                            {__('Move Down', 'top-choices-block')}
                                        </Button>
                                        <Button isDestructive isSmall onClick={() => removeCard(index)}>
                                            {__('Delete', 'top-choices-block')}
                                        </Button>
                                    </div>
                                </div>

                                <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '15px', marginBottom: '15px' }}>
                                    <LocalTextControl
                                        label={__('Product Name', 'top-choices-block')}
                                        value={card.productName}
                                        onChange={(value) => updateCardField(index, 'productName', value)}
                                    />
                                    <LocalTextControl
                                        label={__('Subtitle / Tagline', 'top-choices-block')}
                                        value={card.subTitle}
                                        onChange={(value) => updateCardField(index, 'subTitle', value)}
                                    />
                                </div>

                                <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr 1fr', gap: '15px', marginBottom: '15px' }}>
                                    <SelectControl
                                        label={__('Tag (Top Right)', 'top-choices-block')}
                                        value={card.tag}
                                        options={[
                                            { label: 'No Tag', value: 'none' },
                                            ...tagOptions.map(t => ({ label: t, value: t }))
                                        ]}
                                        onChange={(value) => updateCardField(index, 'tag', value)}
                                    />
                                    <LocalTextControl
                                        label={__('Rating (e.g. 4.8/5)', 'top-choices-block')}
                                        value={card.rating}
                                        onChange={(value) => updateCardField(index, 'rating', value)}
                                    />
                                    <LocalTextControl
                                        label={__('Price (e.g. $89.99)', 'top-choices-block')}
                                        value={card.price}
                                        onChange={(value) => updateCardField(index, 'price', value)}
                                    />
                                </div>

                                 <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr 1fr', gap: '15px', marginBottom: '15px' }}>
                                    <LocalTextControl
                                        label={__('Feature Bullet 1', 'top-choices-block')}
                                        value={card.feature1}
                                        onChange={(value) => updateCardField(index, 'feature1', value)}
                                        maxLength={35}
                                    />
                                    <LocalTextControl
                                        label={__('Feature Bullet 2', 'top-choices-block')}
                                        value={card.feature2}
                                        onChange={(value) => updateCardField(index, 'feature2', value)}
                                        maxLength={35}
                                    />
                                    <LocalTextControl
                                        label={__('Feature Bullet 3', 'top-choices-block')}
                                        value={card.feature3}
                                        onChange={(value) => updateCardField(index, 'feature3', value)}
                                        maxLength={35}
                                    />
                                </div>

                                <div style={{ display: 'grid', gridTemplateColumns: '1fr', gap: '15px', marginBottom: '15px' }}>
                                    <LocalTextControl
                                        label={__('Buy Link URL', 'top-choices-block')}
                                        value={card.btnUrl}
                                        onChange={(value) => updateCardField(index, 'btnUrl', value)}
                                    />
                                </div>

                                <div style={{ marginBottom: '10px' }}>
                                    <label style={{ display: 'block', marginBottom: '5px', fontWeight: '500', fontSize: '13px', color: '#1e293b' }}>
                                        {__('Product Image', 'top-choices-block')}
                                    </label>
                                    <MediaUploadCheck>
                                        <MediaUpload
                                            onSelect={(media) => updateCardField(index, 'imageUrl', media.url)}
                                            allowedTypes={['image']}
                                            value={card.imageUrl}
                                            render={({ open }) => (
                                                <div style={{ display: 'flex', alignItems: 'center', gap: '15px' }}>
                                                    {card.imageUrl ? (
                                                        <img src={card.imageUrl} alt="Preview" style={{ width: '80px', height: '60px', objectFit: 'cover', borderRadius: '4px', border: '1px solid #cbd5e1' }} />
                                                    ) : (
                                                        <div style={{ width: '80px', height: '60px', background: '#f1f5f9', borderRadius: '4px', display: 'flex', alignItems: 'center', justifyContent: 'center', color: '#94a3b8', fontSize: '11px', border: '1px dashed #cbd5e1' }}>No Image</div>
                                                    )}
                                                    <Button variant="secondary" onClick={open}>
                                                        {card.imageUrl ? __('Change Image', 'top-choices-block') : __('Select Image', 'top-choices-block')}
                                                    </Button>
                                                </div>
                                            )}
                                        />
                                    </MediaUploadCheck>
                                </div>
                            </div>
                        );
                    })}

                    <Button 
                        variant="secondary" 
                        onClick={addCard} 
                        isLarge 
                        style={{ marginTop: '10px', width: '100%', justifyContent: 'center' }}
                    >
                        + Add Product Card
                    </Button>
                </div>
            </>
        );
    },

    save: () => {
        // Dynamic rendering block uses server render callback
        return null;
    }
});
