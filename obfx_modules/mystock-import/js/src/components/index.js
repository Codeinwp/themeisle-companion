import PhotoList from "./PhotoList";
import SetFeaturedImage from "./SetFeaturedImage";
import InsertImage from "./InsertImage";

const { PluginSidebar, PluginSidebarMoreMenuItem } = wp.editPost;

const Mystock = () => {
	return (
		<PluginSidebar
			icon="camera"
			name="mystock-sidebar"
			title="MyStockPhotos"
		>
			<div className="mystock-img-container">
				<PhotoList page='1' SetFeaturedImage={SetFeaturedImage} InsertImage={InsertImage} />
			</div>
		</PluginSidebar>
	);
};
export default Mystock;
